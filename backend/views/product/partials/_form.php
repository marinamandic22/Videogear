<?php

use backend\models\forms\ProductForm;
use common\models\ProductVariant;
use common\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ProductForm */
/* @var $form yii\widgets\ActiveForm */

$formId = 'product-form-dynamic';

?>

<div class="product-form">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#general-info" role="tab">
                <?= Yii::t('app', 'General Info') ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#product-variants" role="tab">
                <?= Yii::t('app', 'Product Variants') ?>
            </a>
        </li>
    </ul>
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'id' => $formId
            ]); ?>
            <div class="tab-content">
                <div class="tab-pane active" id="general-info" role="tabpanel">
                    <?= $this->render('_general_info_tab', ['form' => $form, 'model' => $model]); ?>
                </div>
                <div class="tab-pane" id="product-variants" role="tabpanel">
                    <?= $this->render('_product_variant_tab', [
                        'form' => $form,
                        'formId' => $formId,
                        'productVariants' => !empty($model->productVariants) ? $model->productVariants : [new ProductVariant()]
                    ]) ?>
                </div>
            </div>
            <div class="form-group d-flex justify-content-end mb-0">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'), Url::to(['index']), ['class' => 'btn btn-secondary ml-2']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?= FlashMessage::widget(); ?>
</div>

<?php

$js = <<<JS
$('form#{$formId}').on('afterValidate',  function (e, messages, errorAttributes) {
    let errorAttribute = errorAttributes[0];
    if(errorAttribute !== undefined) {
        let tab = $('input' + errorAttribute.input).closest('.tab-pane');
        
        if(!tab.hasClass('active')) {
            let tabId = tab.attr('id');
            let tabSelector = $('.nav-tabs [href="#'+ tabId +'"]');
            
            if(tabSelector.length) {
                tabSelector.tab('show');
            }
        }
    }
});
JS;

$this->registerJs($js);