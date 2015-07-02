<?php

namespace yii2mod\linkpreview\helpers;

/**
 * Class UrlHelper
 * @package yii2mod\linkpreview
 */
class UrlHelper
{
    /**
     * Canonical link for imgSrc
     * @param $imgSrc
     * @param $referrer
     * @return string
     */
    public static function canonicalLink($imgSrc, $referrer)
    {
        if (strpos($imgSrc, "//") === 0) {
            $imgSrc = "http:" . $imgSrc;
        } else if (strpos($imgSrc, "/") === 0) {
            $imgSrc = "http://" . static::canonicalPage($referrer) . $imgSrc;
        } else {
            $imgSrc = "http://" . static::canonicalPage($referrer) . '/' . $imgSrc;
        }
        return $imgSrc;
    }

    /**
     * @param $imgSrc
     * @return mixed
     */
    public static function canonicalImgSrc($imgSrc)
    {
        $imgSrc = str_replace("../", "", $imgSrc);
        $imgSrc = str_replace("./", "", $imgSrc);
        $imgSrc = str_replace(" ", "%20", $imgSrc);
        return $imgSrc;
    }

    /**
     * @param $url
     * @return string
     */
    public static function canonicalPage($url)
    {
        $canonical = "";

        if (substr_count($url, 'http://') > 1 || substr_count($url, 'https://') > 1 || (strpos($url, 'http://') !== false && strpos($url, 'https://') !== false)) {
            return $url;
        }

        if (strpos($url, "http://") !== false) {
            $url = substr($url, 7);
        } else if (strpos($url, "https://") !== false) {
            $url = substr($url, 8);
        }

        for ($i = 0; $i < strlen($url); $i++) {
            if ($url[$i] != "/") {
                $canonical .= $url[$i];
            } else {
                break;
            }
        }
        return $canonical;
    }

    /**
     * @param $pathCounter
     * @param $url
     * @return string
     */
    public static function getImageUrl($pathCounter, $url)
    {
        $src = "";
        if ($pathCounter > 0) {
            $urlBreaker = explode('/', $url);
            for ($j = 0; $j < $pathCounter + 1; $j++) {
                $src .= $urlBreaker[$j] . '/';
            }
        } else {
            $src = $url;
        }
        return $src;
    }
}
