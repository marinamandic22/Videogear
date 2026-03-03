<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', 'Update Product: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="product-update">
    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>
</div>
