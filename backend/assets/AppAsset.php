<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/main.ui.js',
        'js/sweetalert2.all.js',
        'js/modal.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        '\rmrevin\yii\fontawesome\AssetBundle',
        ThemeAsset::class
    ];
}
