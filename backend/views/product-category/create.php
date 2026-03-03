<?php

use common\widgets\ModalContent;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = Yii::t('app', 'Create Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">
    <?php ModalContent::begin(['title' => Html::encode($this->title)]); ?>

    <?= $this->render('partials/_form', [
        'model' => $model,
    ]) ?>

    <?php ModalContent::end(); ?>
</div>
