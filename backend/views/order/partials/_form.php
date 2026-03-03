<?php

use common\helpers\CountryHelper;
use common\models\Order;
use common\widgets\TinyMce;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Order */
/* @var $form ActiveForm */

$pjaxId = 'delivery-card-pjax';

?>

<div class="product-category-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax-id' => $pjaxId
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'delivery_first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'delivery_last_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'delivery_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'delivery_city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'delivery_zip')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'delivery_country')->widget(Select2::class, [
                'data' => CountryHelper::getList()
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'delivery_phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'delivery_notes')->widget(TinyMce::class, [
                'clientOptions' => [
                    'height' => '250'
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
