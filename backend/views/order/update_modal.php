<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('app', 'Update Order: {name}', [
    'name' => "#{$model->code}",
]);

?>
<div class="product-category-update">
    <?php ModalContent::begin(['title' => Html::encode($this->title)]); ?>

    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
