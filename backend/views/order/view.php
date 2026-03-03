<?php

use common\helpers\OrderStatusHelper;
use common\helpers\PriceHelper;
use common\helpers\TimeHelper;
use common\models\Order;
use common\models\User;
use common\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\widgets\Pjax;


/* @var $this View */
/* @var $model Order */
/* @var $user User */
/* @var ActiveDataProvider $orderItemsDataProvider */

$this->title = "Order #{$model->code}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = "#{$model->code}";

$user = User::findWithDeleted()->where(['id' => $model->user_id])->one();

$orderCardPjaxId = 'order-card-pjax';
$statusCardPjaxId = 'status-card-pjax';
$deliveryCardPjaxId = 'delivery-card-pjax';

$orderItemsListId = 'order-items-list-view';

?>
<div class="row">
    <div class="col-xl-7 mb-4">
        <div id="order-items-card" class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="m-0">
                    <i class="fa fa-receipt mr-1"></i>
                    <?= Yii::t('app', 'Order') ?>
                    <strong>#<?= $model->code ?></strong>
                </h5>
                <?php if ($model->created_at) : ?>
                    <div class="ml-auto">
                        <i class="fa fa-calendar-alt mr-1"></i>
                        <?= TimeHelper::formatAsDateTime($model->created_at) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <?php Pjax::begin([
                    'id' => $orderCardPjaxId,
                    'gridId' => $orderItemsListId,
                ]); ?>
                <div class="cb-padding pb-0">
                    <?= ListView::widget([
                        'id' => $orderItemsListId,
                        'dataProvider' => $orderItemsDataProvider,
                        'itemView' => 'partials/_order_item_card',
                    ]) ?>
                </div>
                <?php Pjax::end() ?>
                <div class="cb-padding">
                    <hr>
                    <div class="row">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4">
                            <div class="d-flex align-items-center">
                                <span class="mr-auto"><?= Yii::t('app', 'Subtotal') ?></span>
                                <span><?= PriceHelper::format($model->subtotal) ?></span>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center">
                                <span class="mr-auto"><?= Yii::t('app', 'Total Tax') ?></span>
                                <span><?= PriceHelper::format($model->total_tax) ?></span>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center font-weight-bold">
                                <span class="mr-auto"><?= Yii::t('app', 'Total') ?></span>
                                <span><?= PriceHelper::format($model->total) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <?php Pjax::begin(['id' => $statusCardPjaxId]); ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto col-md-4 d-flex align-items-center">
                        <i class="fa fa-tasks mr-1 fa-lg"></i>
                        <h5 class="m-0 mr-2"><?= Yii::t('app', 'Status') ?></h5>
                        <?= OrderStatusHelper::getStatusBadge($model->status, [
                            'class' => "badge badge-circle badge-sm d-block-inline",
                        ]); ?>
                    </div>

                    <div class="col-auto col-md-8 text-left text-sm-right">
                        <?= Html::a("<i class='fa fa-download mr-2'></i>Download Invoice", Url::to(['order/invoice', 'id' => $model->id]), [
                            'target' => '_blank',
                            'data-pjax' => 0,
                            'class' => 'btn btn-secondary my-2 my-sm-0'
                        ]) ?>
                        <div class="btn-group my-1 my-sm-0">
                            <?= Html::button(OrderStatusHelper::getLabelById($model->status), [
                                'class' => 'btn btn-secondary'
                            ]) ?>
                            <?= Html::button('', [
                                'id' => 'status-dropdown',
                                'class' => 'btn btn-secondary dropdown-toggle',
                                'data-toggle' => 'dropdown',
                                'aria-expanded' => false,
                                'data-reference' => 'parent'
                            ]) ?>
                            <div class="dropdown-menu dropdown-menu-right mw-auto" aria-labelledby="status-dropdown">
                                <?php foreach (OrderStatusHelper::getOptions() as $status => $statusLabel) : ?>
                                    <?php if ($status == $model->status) {
                                        continue;
                                    } ?>
                                    <?= Html::tag('button', $statusLabel, [
                                        'data-href' => Url::to(['order/update-status', 'id' => $model->id, 'status' => $status]),
                                        'data-pjax-id' => $statusCardPjaxId,
                                        'class' => 'dropdown-item btn-control-pjax-action'
                                    ]) ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php Pjax::end() ?>
        <?php Pjax::begin([
            'id' => $deliveryCardPjaxId,
            'options' => [
                'data-pjax-target' => "#{$deliveryCardPjaxId} .card-body"
            ]
        ]); ?>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="m-0">
                    <i class="fa fa-truck mr-1"></i>
                    <?= Yii::t('app', 'Delivery details') ?>
                </h5>
                <?= Html::a('<i class="fa fa-wrench"></i>', Url::to(['order/update', 'id' => $model->id]), [
                    'data-size' => 'modal-lg',
                    'class' => 'btn btn-sm btn-round btn-white btn-just-icon ml-auto btn-modal-control',
                    'title' => Yii::t('app', 'Update'),
                ]); ?>
            </div>
            <div class="card-body">
                <div class="row _lh-lg">
                    <div class="col-xl-6">
                        <div class="mb-2">
                            <i class="fas fa-user mr-2"></i>
                            <?= $model->getCustomerFullName() ?: $user->getFullName() ?>
                        </div>
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <?= Yii::t('app', 'Address'); ?><br>
                        <div class="pl-4">
                            <?= $model->getFormattedDeliveryAddress() ?>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <?php if (!empty($user)) : ?>
                            <div>
                                <i class="fa fa-envelope mr-2"></i>
                                <?= $user->email ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <i class="fa fa-phone mr-2"></i>
                            <?= $model->delivery_phone ?>
                        </div>
                        <div>
                            <i class="fa fa-calendar-alt mr-2"></i>
                            <?= TimeHelper::formatAsDateTime($model->created_at); ?>
                        </div>
                    </div>
                    <?php if (!empty($model->delivery_notes)) : ?>
                        <div class="col-12">
                            <hr>
                            <h5 class="font-weight-bold">Delivery notes:</h5>
                            <hr>
                            <p class="pr-2"><?= $model->delivery_notes ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>
