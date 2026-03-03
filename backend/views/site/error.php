<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
$response = Yii::$app->response;
?>
<div class="error-page">
    <h1 class="error-number"><?= $response->statusCode ?></h1>
    <?php if($response->statusCode == 404) : ?>
        <h5>Sorry but we couldn't find this page</h5>
        <p>This page you are looking for does not exist
            <a href="#">Report this?</a>
        </p>
    <?php elseif($response->statusCode == 403) : ?>
        <h5>Access denied</h5>
        <p>Full authentication is required to access this resource.
            <a href="#">Report this?</a>
        </p>
    <?php elseif($response->statusCode == 500) : ?>
        <h5>Internal Server Error</h5>
        <p>We track these errors automatically, but if the problem persists feel free to contact us. In the meantime, try refreshing.
            <a href="#">Report this?</a>
        </p>
    <?php else : ?>
        <h5><?= $message ?></h5>
        <p></p>
    <?php endif; ?>
    <div class="search">
        <h2>Search</h2>
        <?php $form = ActiveForm::begin([
            'id' => 'error-search-form',
            'method' => 'get',
            'action' => Url::to(['site/index'])
        ]); ?>
            <div class="form-group top_search">
                <div class="input-group">
                    <?= Html::input('text', 'q', '', [
                        'class' => 'form-control',
                        'placeholder' => Yii::t('app', 'Search...'),
                        'autocomplete' => 'off'
                    ]); ?>
                    <span class="input-group-btn">
                        <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default']) ?>
                    </span>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
