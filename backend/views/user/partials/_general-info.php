<?php


use common\helpers\ColorHelper;
use common\helpers\CountryHelper;
use common\helpers\RbacHelper;
use common\helpers\TimeHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>

<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <div class="avatar-wrapper avatar-lg"
                     style="background-color: <?= ColorHelper::generateSeedRgbaColor($model->username) ?>">
                    <span class="text-light"><?= $model->getNameInitials() ?></span>
                </div>
            </div>
            <div class="mt-4">
                <strong class="_font-xl"><?= $model->getFullName(); ?></strong><br>
                <span class="_font-md"><?= TimeHelper::formatAsDateTime($model->created_at); ?></span>
            </div>
            <?php if(Yii::$app->user->can(RbacHelper::ROLE_ADMIN)) : ?>
            <?= Html::tag('span', '<i class="fa fa-wrench"></i>', [
                'data-href' => Url::to(['user/update', 'id' => $model->id]),
                'data-size' => 'modal-lg',
                'class' => 'btn btn-white btn-just-icon btn-loading btn-modal-control align-self-start ml-auto',
                'title' => Yii::t('app', 'Update')
            ]); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row _lh-lg">
            <div class="col-12">
                <i class="fa fa-user mr-2"></i>
                <?= RbacHelper::getRoleLabel($model->getRole()); ?>
            </div>
            <div class="col-12">
                <i class="fa fa-envelope mr-2"></i>
                <?= $model->email ?>
            </div>
            <div class="col-12">
                <i class="fa fa-phone mr-2"></i>
                <?= $model->phone ?>
            </div>
            <div class="col-12">
                <i class="fa fa-calendar-alt mr-2"></i>
                <?= TimeHelper::formatAsDateTime($model->created_at); ?>
            </div>
            <hr>
            <div class="col-12">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <?= Yii::t('app', 'Billing Address'); ?><br>
                <div class="pl-4">
                    <?php if (!empty($model->address)) : ?>
                        <div><?= $model->address ?></div>
                    <?php endif; ?>
                    <?php if (!empty($model->city)) : ?>
                        <div><?= $model->city ?></div>
                    <?php endif; ?>
                    <?php if (!empty($model->zip)) : ?>
                        <div><?= $model->zip ?></div>
                    <?php endif; ?>
                    <?php if (!empty($model->country)) : ?>
                        <div><?= CountryHelper::getNameByCode($model->country) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
