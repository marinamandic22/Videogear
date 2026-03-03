<?php


use common\helpers\BaseHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\forms\ChangePasswordForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'current_password')->passwordInput() ?>
        </div>
        <div class="col-12">
            <?php $newPasswordLabel = $model->getAttributeLabel('new_password') . BaseHelper::getPasswordInfoIcon(); ?>
            <?= $form->field($model, 'new_password')->passwordInput()->label($newPasswordLabel); ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
        </div>
    </div>
    <div class="form-group d-flex justify-content-end my-3">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-modal-control-submit btn-loading']) ?>
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-secondary ml-2', 'data-dismiss' => 'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
