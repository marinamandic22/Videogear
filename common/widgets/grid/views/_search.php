<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $title string */

$queryParam = Yii::$app->request->getQueryParam('q');
$queryParams = Yii::$app->request->queryParams;

?>

<div class="shared-search">
    <?php $form = ActiveForm::begin([
        'id' => 'shared-search-filter',
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
        <h1 class="m-0 mr-3 py-2 py-md-0"><?= $title ?? Html::encode($this->title) ?></h1>
        <div class="form-group top_search my-0 <?= !empty($queryParam) ? ' flex-grow-1' : '' ?>">
            <div class="input-group m-0">
                <?= Html::input('text', 'q', $queryParam, [
                    'class' => 'form-control',
                    'placeholder' => Yii::t('app', 'Search...'),
                    'autocomplete' => 'off'
                ]); ?>
                <span class="input-group-btn d-flex">
                    <?php if (!empty($queryParam)) : ?>
                        <?= Html::a('<i class="fa fa-times"></i>', Url::to(ArrayHelper::merge($queryParams, ['', 'q' => ''])), [
                            'class' => 'btn btn-default search-dismiss'
                        ]) ?>
                    <?php endif; ?>
                    <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
                </span>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
