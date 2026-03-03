<?php

use common\models\ProductCategory;
use common\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

$gridId = ProductCategory::INDEX_GRID_ID;
$pjaxId = 'product-category-pjax';
$pjaxLoaderTarget = "#{$gridId} tbody";

?>
<div class="product-category-index">
    <?php Pjax::begin([
        'id' => $pjaxId,
        'gridId' => $gridId,
        'timeout' => 5000,
        'options' => ['data-pjax-loader-target' => $pjaxLoaderTarget]
    ]); ?>

    <?= $this->render('partials/_grid', [
        'dataProvider' => $dataProvider,
        'gridId' => $gridId,
        'pjaxId' => $pjaxId
    ]); ?>

    <?php Pjax::end(); ?>
</div>
