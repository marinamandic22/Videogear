<?php

namespace common\widgets\dropzone;

use Yii;
use yii\bootstrap4\InputWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Dropzone extends InputWidget
{
    public $enableSorting = false;

    public $items = [];

    public function run()
    {
        $this->clientOptions = ArrayHelper::merge([
            'url' => Url::to(['file/upload']),
            'paramName' => 'UploadForm[files]',
            'acceptedFiles' => 'image/*',
            'addRemoveLinks' => true,
            'uploadMultiple' => true,
            'maxFiles' => 15,
            'dictDefaultMessage' => Yii::t('app', 'Drop files here for upload'),
            'dictMaxFilesExceeded' => Yii::t('app', 'Max number of files has been reached !')
        ], $this->clientOptions);

        $this->options = ArrayHelper::merge($this->options, ['id' => $this->getInputId()]);
        if ($this->hasModel()) {
            $input = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->options);
        }

        echo Html::tag('div', $input, ['id' => $this->getDropzoneId(), 'class' => '']);

        $this->registerAssets();
        parent::run();
    }

    public function registerAssets()
    {
        DropzoneAsset::register($this->view);
        $options = Json::encode($this->clientOptions);
        $config = Json::encode([
            'target' => "#{$this->getDropzoneId()}",
            'input' => "input#{$this->getInputId()}",
            'items' => $this->items,
            'initialItems' => $this->items,
            'enableSorting' => $this->enableSorting
        ]);

        $this->view->registerJs("dropzone.initialize({$config}, {$options});");
    }

    protected function getDropzoneId()
    {
        return ArrayHelper::getValue($this->clientOptions, 'id', "dropzone-{$this->getId()}");
    }

    protected function getInputId()
    {
        if ($this->hasModel()) {
            return Html::getInputId($this->model, $this->attribute);
        }

        return "dropzone-input-{$this->attribute}";
    }
}