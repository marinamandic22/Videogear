<?php


use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Change Password');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="change-password">
    <?php ModalContent::begin(['title' => Html::encode($this->title)]) ?>

    <?= $this->render('partials/_change-password-form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
