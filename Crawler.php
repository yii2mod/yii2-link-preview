<?php

namespace yii2mod\linkpreview;

use Embed\Adapters\Adapter;
use Embed\Embed;
use Embed\Exceptions\InvalidUrlException;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class Crawler
 *
 * @package yii2mod\linkpreview
 */
class Crawler extends Object
{
    /**
     * @var string content given from the widget
     */
    public $content;

    /**
     * @var array Embed config
     */
    public $config = [];

    /**
     * @var string url regex
     */
    public $regexUrl = '/https?\:\/\/[^\" ]+/i';

    /**
     * Return page info
     *
     * @return array|Adapter
     *
     * @throws InvalidConfigException
     */
    public function getPageInfo()
    {
        if (empty($this->content)) {
            throw new InvalidConfigException("The 'content' property is required.");
        }

        $url = $this->getUrlFromContent();
        if (!empty($url)) {
            try {
                return Embed::create($url, $this->config);
            } catch (InvalidUrlException $e) {
                // Invalid url
            }
        }

        return [];
    }

    /**
     * Get link from content
     *
     * @return mixed|null
     */
    protected function getUrlFromContent()
    {
        preg_match($this->regexUrl, $this->content, $matches);

        return ArrayHelper::getValue($matches, 0);
    }
}
