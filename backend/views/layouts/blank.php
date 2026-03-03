<?php

/**
 * @var string $content
 * @var View $this
 */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this)

?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <section class="empty-layout">
        <div class="container">
            <?= $content ?>
        </div>
    </section>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>