<?php

use common\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'product-grid';
$pjaxId = 'product-index-pjax';
$pjaxLoaderTarget = "#{$gridId} tbody";

?>

<div class="product-index">
    <?php Pjax::begin([
        'id' => $pjaxId,
        'gridId' => $gridId,
        'options' => [
            'data-pjax-loader-target' => $pjaxLoaderTarget
        ]
    ]); ?>
    <?= $this->render('partials/_grid', [
        'dataProvider' => $dataProvider,
        'pjaxId' => $pjaxId,
        'gridId' => $gridId
    ]); ?>
    <?php Pjax::end(); ?>
</div>
