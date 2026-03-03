<?php

use common\helpers\BaseHelper;
use common\helpers\ProductCategoryHelper;
use common\models\ProductCategory;
use common\widgets\dropzone\Dropzone;
use common\widgets\TinyMce;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="product-category-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'data-grid-id' => ProductCategory::INDEX_GRID_ID,
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'parent_category_id')->widget(Select2::class, [
                'data' => ProductCategoryHelper::getParentCategorySelectList($model),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select parent category...')
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'description')->widget(TinyMce::class, [
                'clientOptions' => [
                    'height' => '250'
                ]
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'coverImageIds')->widget(Dropzone::class, [
                'items' => $model->coverImage ? BaseHelper::convertImagesToDropzoneFormat([$model->coverImage]) : [],
                'clientOptions' => [
                    'maxFiles' => 1
                ]
            ]) ?>
        </div>
    </div>
    <div class="form-group d-flex justify-content-end my-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-modal-control-submit btn-loading']) ?>
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-secondary ml-2', 'data-dismiss' => 'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
