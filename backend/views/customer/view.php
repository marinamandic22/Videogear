<?php

use yii\data\ActiveDataProvider;
use common\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $orderDataProvider ActiveDataProvider */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'user-order-grid';
$pjaxId = 'user-order-index-pjax';
$pjaxLoaderTarget = "#{$gridId} tbody";

?>

<div class="user-view">
    <div class="row">
        <div class="col-lg-5 mb-3">
            <?= $this->render('partials/_general-info', ['model' => $model]); ?>
        </div>
        <div class="col-lg-7">
            <?= $this->render('partials/_widget-cards', ['model' => $model]); ?>
            <div class="card">
                <div class="card-body">
                    <?php Pjax::begin([
                        'id' => $pjaxId,
                        'enablePushState' => false,
                        'timeout' => 5000,
                        'options' => ['data-pjax-loader-target' => $pjaxLoaderTarget]
                    ]); ?>
                    <?= $this->render('../order/partials/_grid', [
                        'dataProvider' => $orderDataProvider,
                        'pjaxId' => $pjaxId,
                        'gridId' => $gridId,
                        'customerColumnVisible' => false
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
