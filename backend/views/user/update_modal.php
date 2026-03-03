<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update User: {name}', [
    'name' => $model->getFullName(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="user-update">
    <?php ModalContent::begin([
        'title' => Html::encode($this->title)
    ]) ?>

    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
