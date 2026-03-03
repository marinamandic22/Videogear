<?php


use common\helpers\BaseHelper;
use common\models\Product;
use common\widgets\dropzone\Dropzone;
use common\widgets\TinyMce;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $form ActiveForm */
/* @var $model Product */

?>

<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'category_id')->widget(Select2::class, [
                    'data' => !empty($model->category) ? [$model->category_id => $model->category->name] : [],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select a category...')
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'ajax' => [
                            'url' => Url::to(['product-category/suggest']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'cache' => true
                        ],
                    ],
                ]); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'sku')->textInput(); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'price')->textInput(); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'quantity')->textInput(); ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'short_description')->textarea([
                    'rows' => 3
                ]); ?>
            </div>
            <div class="col-12">
                <?= $form->field($model, 'description')->widget(TinyMce::class, []); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'productImageIds')->widget(Dropzone::class, [
                    'items' => BaseHelper::convertImagesToDropzoneFormat($model->getAllProductImages()),
                    'enableSorting' => true
                ]); ?>
            </div>
        </div>
    </div>
</div>
