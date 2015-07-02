<?php

namespace yii2mod\linkpreview;

use yii\web\AssetBundle;

/**
 * Class LinkPreviewAsset
 * @package yii2mod\linkpreview
 */
class LinkPreviewAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/yii2mod/yii2-link-preview/assets';

    /**
     * @var array
     */
    public $js = [
        'js/linkPreview.js',
    ];

    /**
     * @var array
     */
    public $css = [
        'css/linkPreview.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}