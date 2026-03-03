<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = Yii::t('app', 'Update Category: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="product-category-update">
    <?php ModalContent::begin(['title' => Html::encode($this->title)]); ?>

    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
