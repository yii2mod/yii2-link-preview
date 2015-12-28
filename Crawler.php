<?php

namespace yii2mod\linkpreview;

use yii\helpers\ArrayHelper;
use yii2mod\linkpreview\helpers\ContentHelper;
use yii2mod\linkpreview\helpers\MediaHelper;
use yii2mod\linkpreview\helpers\UrlHelper;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\HtmlPurifier;

/**
 * Class Crawler
 * @package yii2mod\linkpreview
 */
class Crawler extends Object
{
    /**
     * @var string content given from widget
     */
    public $content;

    /**
     * @var array default curl options
     */
    public $curlOptions = [];

    /**
     * @var array html purifier settings
     */
    public $htmlPurifierSettings = [
        'HTML.Allowed' => ''
    ];

    /**
     * @var string page url
     */
    protected $url;

    /**
     * @var string page title
     */
    protected $title;

    /**
     * @var string page description
     */
    protected $description;

    /**
     * @var string image url from page content
     */
    protected $imageUrl;

    /**
     * Initialize object
     */
    public function init()
    {
        $this->curlOptions = ArrayHelper::merge([
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.65 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_ENCODING => 'UTF-8'
        ], $this->curlOptions);

        parent::init();
    }

    /**
     * Return page preview array data in json format
     * @return null|array
     */
    public function getPagePreview()
    {
        $this->url = $this->getUrlFromContent();
        if ($this->url !== null) {
            if (ContentHelper::isImageUrl($this->url)) {
                $this->imageUrl = $this->url;
            } else {
                $pageData = $this->performRequest();
                if (!$pageData["content"] && strpos($this->url, "//www.") === false) {
                    if (strpos($this->url, "http://") !== false) {
                        $this->url = str_replace("http://", "http://www.", $this->url);
                    } elseif (strpos($this->url, "https://") !== false) {
                        $this->url = str_replace("https://", "https://www.", $this->url);
                    }
                    $pageData = $this->performRequest();
                }
                if ($pageData === null) {
                    return $this->getResponseData();
                }
                $this->url = $pageData['url'];
                $content = $pageData['content'];
                $metaTags = ContentHelper::getMetaTags($content);
                $this->title = $this->getTitle($content, $metaTags);
                $this->description = $this->getDescription($content, $metaTags);
                $media = $this->getMedia();
                $this->imageUrl = count($media) === 0 ? ContentHelper::trimText($metaTags["image"]) : $media['imgUrl'];
                if (empty($this->imageUrl)) {
                    $this->imageUrl = ContentHelper::getImageSrc($content, $this->url);
                }
            }
            return $this->getResponseData();
        }
        return null;
    }

    /**
     * Get link from content
     * @param null $default
     * @return mixed|null
     */
    protected function getUrlFromContent($default = null)
    {
        $this->content = str_replace("\n", " ", $this->content);
        if (preg_match(ContentHelper::$regexList['url'], $this->content, $match)) {
            if (strpos($match[0], " ") === 0) {
                $match[0] = "http://" . substr($match[0], 1);
            }
            return str_replace("https://", "http://", $match[0]);
        }
        return $default;
    }

    /**
     * Performs HTTP request
     * Return page content, url and header info
     *
     * @throws Exception if request failed
     * @return mixed
     */
    protected function performRequest()
    {
        $response = [];
        $curl = curl_init($this->url);
        curl_setopt_array($curl, $this->curlOptions);
        $body = curl_exec($curl);
        $header = curl_getinfo($curl);
        if ($body !== false) {
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($responseCode >= 200 && $responseCode < 300) {
                $response['content'] = $body;
                $response['url'] = $header['url'];
                return $response;
            }
        }
        return null;
    }

    /**
     * Get page media data
     * @return array
     */
    protected function getMedia()
    {
        $result = [];
        foreach (MediaHelper::$videoServiceConfig as $domainName => $methodName) {
            if (strpos($this->url, $domainName) !== false) {
                $result = MediaHelper::$methodName($this->url);
            }
        }
        return $result;
    }

    /**
     * Get page title
     * @param $content
     * @param $metaTags
     * @return string
     */
    protected function getTitle($content, $metaTags)
    {
        $title = ContentHelper::trimText($metaTags["title"]);
        if (empty($title)) {
            if (preg_match(ContentHelper::$regexList['title'], str_replace("\n", " ", $content), $matching)) {
                $title = $matching[2];
            }
        }
        if (ContentHelper::isJson($title)) {
            $title = "";
        }
        return ContentHelper::trimText($title);
    }

    /**
     * Get page description
     * @param $content
     * @param $metaTags
     * @return mixed|string
     */
    protected function getDescription($content, $metaTags)
    {
        $description = ContentHelper::trimText($metaTags["description"]);
        if (empty($description)) {
            $description = ContentHelper::parse($content);
        }
        if (ContentHelper::isJson($description)) {
            $description = "";
        }
        $description = HtmlPurifier::process($description, $this->htmlPurifierSettings);

        return ContentHelper::trimText($description);
    }

    /**
     * Return response array data
     * @return array
     */
    protected function getResponseData()
    {
        return [
            'status' => 'success',
            'title' => $this->title,
            'url' => $this->url,
            'canonicalUrl' => UrlHelper::canonicalPage($this->url),
            'description' => $this->description,
            'image' => $this->imageUrl
        ];
    }
}
