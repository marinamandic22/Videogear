<?php

use common\helpers\PriceHelper;
use common\models\Order;
use common\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Home');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbsHomeLink'] = false;


$gridId = 'dashboard-grid';
$pjaxId = 'dashboard-index-pjax';
$pjaxLoaderTarget = "#{$gridId} tbody";

$query = $dataProvider->query;

?>

<div class="daily-board-index">
    <div class="row mb-4">
        <div class="col-lg-6 col-xl-3 mb-3">
            <div class="widget-card card">
                <div class="card-body d-flex p-0">
                    <div class="icon-container icon-picton">
                        <i class="fa fa-spinner fa-2x"></i>
                    </div>
                    <div class="p-3 font-weight-bold">
                        <h6><?= Yii::t('app', 'Pending') ?></h6>
                        <h4 class="m-0"><?= $searchModel->getPendingOrdersCount($query) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-3">
            <div class="widget-card card">
                <div class="card-body d-flex p-0">
                    <div class="icon-container icon-violet">
                        <i class="fab fa-stack-overflow"></i>
                    </div>
                    <div class="p-3 font-weight-bold">
                        <h6><?= Yii::t('app', 'In progress') ?></h6>
                        <h4 class="m-0"><?= $searchModel->getProcessingOrdersCount($query) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-3">
            <div class="widget-card card">
                <div class="card-body d-flex p-0">
                    <div class="icon-container icon-success">
                        <i class="fa fa-check fa-2x"></i>
                    </div>
                    <div class="p-3 font-weight-bold">
                        <h6><?= Yii::t('app', 'Completed') ?></h6>
                        <h4 class="m-0"><?= $searchModel->getCompletedOrdersCount($query) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3 mb-3">
            <div class="widget-card card border-0">
                <div class="card-body d-flex p-0">
                    <div class="icon-container icon-primary">
                        <i class="fa fa-coins fa-2x"></i>
                    </div>
                    <div class="p-3 font-weight-bold">
                        <h6><?= Yii::t('app', 'Total Revenue') ?></h6>
                        <h4 class="m-0"><?= PriceHelper::format($searchModel->getOrdersTotalSum($query)) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::begin([
        'id' => $pjaxId,
        'gridId' => $gridId,
        'options' => ['data-pjax-loader-target' => $pjaxLoaderTarget]
    ]); ?>
    <?= $this->render('partials/_grid', [
        'dataProvider' => $dataProvider,
        'pjaxId' => $pjaxId,
        'gridId' => $gridId
    ]); ?>
    <?php Pjax::end(); ?>
</div>
