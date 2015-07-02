<?php

namespace yii2mod\linkpreview\helpers;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ContentHelper
 * @package yii2mod\linkpreview
 */
class ContentHelper
{
    /**
     * @var array Regex list for url, content, title, etc...
     */
    public static $regexList = [
        'url' => "/(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i",
        'image' => "/<img(.*?)src=(\"|\')(.+?)(gif|jpg|png|bmp)(.*?)(\"|\')(.*?)(\/)?>(<\/img>)?/",
        'imagePrefix' => "/\.(jpg|png|gif|bmp)$/i",
        'src' => '/src=(\"|\')(.+?)(\"|\')/i',
        'http' => "/https?\:\/\//i",
        'content1' => '/content="(.*?)"/i',
        'content2' => "/content='(.*?)'/i",
        'meta' => '/<meta(.*?)>/i',
        'title' => "/<title(.*?)>(.*?)<\/title>/i",
        'script' => "/<script(.*?)>(.*?)<\/script>/i",
    ];

    /**
     * @var array charset list
     */
    public static $charsetList = [
        "UTF-8",
        "EUC-CN",
        "EUC-JP",
        "EUC-KR",
        'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
        'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
        'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
        'Windows-1251', 'Windows-1252', 'Windows-1254',
    ];

    /**
     * Parse text
     * Get content from paragraph or div
     * @param $text
     * @return mixed
     */
    public static function parse($text)
    {
        $contentSpan = static::getTagContent("span", $text);
        $contentParagraph = static::getTagContent("p", $text);
        $contentDiv = static::getTagContent("div", $text);

        if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) >= strlen($contentDiv)) {
            $content = $contentParagraph;
        } else if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) < strlen($contentDiv)) {
            $content = $contentDiv;
        } else {
            $content = $contentParagraph;
        }
        return $content;
    }

    /**
     * Get tag content
     * @param $tag
     * @param $string
     * @return mixed
     */
    public static function getTagContent($tag, $string)
    {
        $pattern = "/<$tag(.*?)>(.*?)<\/$tag>/i";

        preg_match_all($pattern, $string, $matches);
        $content = "";
        if (!isset($matches[0]) || empty($matches[0])) {
            return $content;
        }
        for ($i = 0; $i < count($matches[0]); $i++) {
            $currentMatch = strip_tags($matches[0][$i]);
            if (strlen($currentMatch) >= 120) {
                $content = $currentMatch;
                break;
            }
        }
        if ($content == "") {
            preg_match($pattern, $string, $matches);
            $content = $matches[0];
        }
        return str_replace("&nbsp;", "", $content);
    }

    /**
     * Check if url is a image url
     * @param $url
     * @return bool
     */
    public static function isImageUrl($url)
    {
        if (preg_match(ContentHelper::$regexList['imagePrefix'], $url)) {
            return true;
        }
        return false;
    }

    /**
     * Get image src from text
     * @param $text
     * @param $url
     * @param int $minWidth
     * @param int $minHeight
     * @param string $default
     * @return string
     */
    public static function getImageSrc($text, $url, $minWidth = 40, $minHeight = 15, $default = "")
    {
        $crawler = new Crawler($text);
        $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });
        foreach ($images as $image) {
            $pathCounter = substr_count($image, "../");
            if (!preg_match(self::$regexList['http'], $image)) {
                $imgSrc = UrlHelper::getImageUrl($pathCounter, UrlHelper::canonicalLink($image, $url));
            } else {
                $imgSrc = $image;
            }
            if ($imageSize = @getimagesize($imgSrc)) {
                $width = $imageSize[0];
                $height = $imageSize[1];
                if ($width > $minWidth && $height > $minHeight) {  // avoids getting very small images
                    return $imgSrc;
                }
            }
        }

        return $default;
    }

    /**
     * Get meta tags from content
     * @param $content
     * @return bool
     */
    public static function getMetaTags($content)
    {
        $result = false;
        if (!empty($content)) {
            $encodingCheck = mb_detect_encoding($content, static::$charsetList, true);
            $encoding = ($encodingCheck === false) ? "UTF-8" : $encodingCheck;
            $result = static::getMetaTagsEncoding($content, $encoding);
        }

        return $result;
    }

    /**
     * Get meta tags encoding
     * @param $content
     * @param $encoding
     * @return array|bool
     */
    public static function getMetaTagsEncoding($content, $encoding)
    {
        $result = false;
        $metaTags = ["url" => "", "title" => "", "description" => "", "image" => ""];
        if (!empty($content)) {
            $doc = new \DOMDocument();
            @$doc->loadHTML("<?xml encoding={$encoding}?>" . $content);
            // specify the output encoding
            $metaData = $doc->getElementsByTagName('meta');
            for ($i = 0; $i < $metaData->length; $i++) {
                /* @var $meta \DOMElement */
                $meta = $metaData->item($i);
                if ($meta->getAttribute('name') == 'description')
                    $metaTags["description"] = $meta->getAttribute('content');
                if ($meta->getAttribute('name') == 'keywords')
                    $metaTags["keywords"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:title')
                    $metaTags["title"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:image')
                    $metaTags["image"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:description')
                    $metaTags["og_description"] = $meta->getAttribute('content');
                if ($meta->getAttribute('property') == 'og:url')
                    $metaTags["url"] = $meta->getAttribute('content');
            }
            if (!empty($metaTags["og_description"])) {
                $metaTags["description"] = $metaTags["og_description"];
            }
            if (empty($metaTags["title"])) {
                $nodes = $doc->getElementsByTagName('title');
                if (isset($nodes->item(0)->nodeValue)) {
                    $metaTags["title"] = $nodes->item(0)->nodeValue;
                }
            }
            $result = $metaTags;
        }
        return $result;
    }


    /**
     * Trim text
     * @param $content
     * @return string
     */
    public static function trimText($content)
    {
        return trim(str_replace("\n", " ", str_replace("\t", " ", preg_replace("/\s+/", " ", $content))));
    }

    /**
     * Checks if string is JSON
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
