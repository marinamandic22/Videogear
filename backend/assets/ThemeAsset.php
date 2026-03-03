<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/theme.css'
    ];

    public $js = [
        'js/theme.js'
    ];
}