<?php

use common\helpers\ColorHelper;
use common\helpers\TimeHelper;
use common\models\User;
use yii\base\View;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var View $this
 * @var User $model
 * @var string $controllerId
 */

?>

<div class="d-flex align-items-center">
    <div class="mr-2">
        <div class="avatar-wrapper"
             style="background-color: <?= ColorHelper::generateSeedRgbaColor($model->username) ?>">
            <span class="text-light"><?= $model->getNameInitials() ?></span>
        </div>
    </div>
    <div>
        <div class="name">
            <?= !$model->isNewRecord && !$model->isSoftDeleted() ? Html::a($model->getFullName(), Url::to(["{$controllerId}/view", 'id' => $model->id]), [
                'data-pjax' => 0,
                'class' => 'mr-2'
            ]) : $model->getFullName()
            ?>
        </div>
        <div class="date">
            <small><?= TimeHelper::formatAsDateTime($model->created_at); ?></small>
        </div>
    </div>
</div>
