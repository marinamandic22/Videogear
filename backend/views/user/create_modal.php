<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-create">
    <?php ModalContent::begin(['title' => Html::encode($this->title)]) ?>

    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
