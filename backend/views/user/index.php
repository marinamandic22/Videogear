<?php

use common\models\User;
use common\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;

$gridId = User::INDEX_GRID_ID;
$pjaxId = 'employees-index-pjax';
$pjaxLoaderTarget = "#{$gridId} tbody";

?>

<div class="employees-index">
    <?php Pjax::begin([
        'id' => $pjaxId,
        'gridId' => $gridId,
        'options' => ['data-pjax-loader-target' => $pjaxLoaderTarget]
    ]); ?>
    <?= $this->render('partials/_grid', [
        'dataProvider' => $dataProvider,
        'pjaxId' => $pjaxId,
        'gridId' => $gridId
    ]); ?>
    <?php Pjax::end(); ?>
</div>
