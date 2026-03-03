<?php


use common\helpers\PriceHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>

<div class="row">
    <div class="col-md-6 pr-3 pr-md-2 pb-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <strong><?= Yii::t('app', 'Total Spent') ?></strong>
                <h4 class="mb-0"><?= PriceHelper::format($model->getTotalSpent()) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 pl-3 pl-md-2 pb-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <strong><?= Yii::t('app', 'Average Order') ?></strong>
                <h4 class="mb-0"><?= PriceHelper::format($model->getAverageOrderValue()) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 pr-3 pr-md-2 pb-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <strong><?= Yii::t('app', 'Total Orders') ?></strong>
                <h4 class="mb-0"><?= number_format($model->getTotalOrders()) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 pl-3 pl-md-2 pb-3">
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <strong><?= Yii::t('app', 'Total Items') ?></strong>
                <h4 class="mb-0"><?= number_format($model->getTotalOrderItems()) ?></h4>
            </div>
        </div>
    </div>
</div>
