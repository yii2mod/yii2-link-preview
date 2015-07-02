<?php

namespace yii2mod\linkpreview\helpers;

use yii\helpers\Json;

/**
 * Class MediaHelper
 * @package yii2mod\linkpreview
 *
 * This class mounts the iframe embed code for the video services below
 */
class MediaHelper
{
    /**
     * Video Service Config
     * keys - domain names
     * values - function name in this class
     * @var array
     */
    public static $videoServiceConfig = [
        'youtube.com' => 'mediaYoutube',
        'vimeo.com' => 'mediaVimeo',
        'vine.co' => 'mediaVine',
        'metacafe.com' => 'mediaMetacafe',
        'dailymotion.com' => 'mediaDailymotion',
        'collegehumor.com' => 'mediaCollegehumor',
        'blip.tv' => 'mediaBlip',
        'funnyordie.com' => 'mediaFunnyordie'
    ];

    /**
     * Return iframe code for Youtube videos
     * @param $url
     * @return array
     */
    public static function mediaYoutube($url)
    {
        $media = [];
        if (preg_match("/(.*?)v=(.*?)($|&)/i", $url, $matching)) {
            $vid = $matching[2];
            $media['imgUrl'] = "http://i2.ytimg.com/vi/$vid/hqdefault.jpg";
        }
        return $media;
    }

    /**
     * Return iframe code for Vine videos
     * @param $url
     * @return array
     */
    public static function mediaVine($url)
    {
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $breakUrl = explode("/", $url);
        $media = [];
        if (isset($breakUrl[2]) && $breakUrl[2] != "") {
            $vid = $breakUrl[2];
            $media['imgUrl'] = static::mediaVineThumb($vid);
        }
        return $media;
    }

    /**
     * get media vine thumb
     * @param $id
     * @return bool
     */
    public static function mediaVineThumb($id)
    {
        $vine = file_get_contents("http://vine.co/v/{$id}");
        preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);

        return ($matches[1]) ? $matches[1] : false;
    }

    /**
     * Return iframe code for Vimeo videos
     * @param $url
     * @return array
     */
    public static function mediaVimeo($url)
    {
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $breakUrl = explode("/", $url);
        $media = [];
        if ($breakUrl[1] != "") {
            $imgId = $breakUrl[1];
            $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgId.php"));
            $media['imgUrl'] = $hash[0]['thumbnail_large'];
        }
        return $media;
    }

    /**
     * Return iframe code for Metacafe videos
     * @param $url
     * @return array
     */
    public static function mediaMetacafe($url)
    {
        $media = [];
        preg_match('|metacafe\.com/watch/([\w\-\_]+)(.*)|', $url, $matching);
        if ($matching[1] != "") {
            $vid = $matching[1];
            $vtitle = trim($matching[2], "/");
            $media['imgUrl'] = "http://s4.mcstatic.com/thumb/{$vid}/0/6/videos/0/6/{$vtitle}.jpg";
        }
        return $media;
    }

    /**
     * Return iframe code for Dailymotion videos
     * @param $url
     * @return array
     */
    public static function mediaDailymotion($url)
    {
        $media = [];
        $id = strtok(basename($url), '_');
        if ($id != "") {
            $media['imgUrl'] = "http://www.dailymotion.com/thumbnail/160x120/video/$id";
        }
        return $media;
    }

    /**
     * Return iframe code for College Humor videos
     * @param $url
     * @return array
     */
    public static function mediaCollegehumor($url)
    {
        $media = [];
        preg_match('#(?<=video/).*?(?=/)#', $url, $matching);
        $id = $matching[0];
        if ($id != "") {
            $hash = file_get_contents("http://www.collegehumor.com/oembed.json?url=http://www.dailymotion.com/embed/video/$id");
            $hash = Json::decode($hash, true);
            $media['imgUrl'] = $hash['thumbnail_url'];
        }
        return $media;

    }

    /**
     * Return iframe code for Blip videos
     * @param $url
     * @return array
     */
    public static function mediaBlip($url)
    {
        $media = array();
        if ($url != "") {
            $hash = file_get_contents("http://blip.tv/oembed?url=$url");
            $hash = Json::decode($hash, true);
            $media['imgUrl'] = $hash['thumbnail_url'];
        }
        return $media;
    }

    /**
     * Return iframe code for Funny or Die videos
     * @param $url
     * @return array
     */
    public static function mediaFunnyordie($url)
    {
        $media = [];
        if ($url != "") {
            $hash = file_get_contents("http://www.funnyordie.com/oembed.json?url=$url");
            $hash = Json::decode($hash, true);
            $media['imgUrl'] = $hash['thumbnail_url'];
        }
        return $media;
    }

}
