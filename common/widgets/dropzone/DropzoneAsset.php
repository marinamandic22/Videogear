<?php

namespace common\widgets\dropzone;

use common\widgets\dropzone\assets\depends\DropzoneJsAsset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/dropzone/assets/';

    public $js = [
        'js/dropzone.js',
    ];

    public $css = [
        'css/dropzone.css',
    ];

    public $depends = [
        DropzoneJsAsset::class,
        JqueryAsset::class,
        JuiAsset::class
    ];

}