<?php

use common\components\image\ImageSpecification;
use common\helpers\PriceHelper;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductVariant;
use yii\helpers\Url;

/* @var OrderItem $model */
/* @var Product $product */
/* @var ProductVariant $productVariant */

$product = $model->getProductWithDeleted();
$productVariant = $model->getProductVariantWithDeleted();

$imageUrl = Url::to([
    '/image/view', 'id' => $product->cover_image_id,
    'spec' => ImageSpecification::THUMB_MEDIUM_SQUARED
]);

?>
<?php if (!empty($product)) : ?>
    <div class="d-flex align-items-center line-order-item">
        <?php if ($product->cover_image_id) : ?>
            <div class="image">
                <img src="<?= $imageUrl ?>" alt="Cover image">
            </div>
        <?php endif; ?>
        <div class="name">
            <strong><?= $product->name ?></strong><br>
            <small><?= $product->short_description ?></small>
        </div>
        <div class="quantity">
            <?= $model->quantity ?> x <?= PriceHelper::format($model->price) ?>
        </div>
        <div class="total">
            <strong><?= PriceHelper::format($model->total) ?></strong>
        </div>
    </div>
    <hr>
<?php endif; ?>
