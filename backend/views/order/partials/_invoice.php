<?php

use common\helpers\PriceHelper;
use common\helpers\TimeHelper;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductVariant;

/**
 * @var Order $model
 * @var OrderItem $orderItem
 * @var Product $product
 * @var ProductVariant $productVariant
 */

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= Yii::t("app", "Invoice") ?></title>

    <style>
        @page {
            size: auto;
            odd-header-name: page-header;
            even-header-name: page-header;
            odd-footer-name: page-footer;
            even-footer-name: page-footer;
        }

        htmlpageheader,
        htmlpagefooter {
            display: none;
        }

        table {
            font-family: 'Times New Roman', 'Arial', 'Helvetica', sans-serif;
            font-size: 13px;
            line-height: 22px;
            width: 100%;
        }

        table th {
            padding: 8px 12px;
            border-top: 1px solid;
            border-bottom: 1px solid;
            border-color: #808080;
            background-color: #eee;
        }

        table td {
            padding: 2px 12px;
        }

        table.header-date {
            border-collapse: collapse;
        }

        table.header {
            padding-top: 24px;
        }

        table.header,
        table.body {
            padding-bottom: 48px;
        }

        table.footer {
            border-collapse: collapse;
        }

        table.footer td,
        table.footer th {
            border: 1px solid #808080;
        }

        table.footer td {
            padding: 16px 12px;
        }

        table.body td {
            border-bottom: 1px solid #808080;
        }

        .price {
            font-weight: bold;
            font-size: 11px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="invoice-box" style="height: 100%;">
    <htmlpageheader name="page-header">
        <table cellspacing="0">
            <tr>
                <td style="width: 50%">
                    <img src="img/brand/logo.png" style="width:100%; max-width:150px; padding-bottom: 25px"><br>
                    Video Gear LLC,
                    <?= Yii::$app->params['contact']['address'] ?>
                </td>
                <td valign="top" class="text-right" style="line-height: 3">
                    <div style="font-size: 24px; font-weight: bolder;">
                        <?= Yii::t("app", "Invoice") ?>
                    </div>
                    <table class="header-date">
                        <tr>
                            <td class="text-right" style="padding: 0 10px"><?= Yii::t("app", "Order #") ?>:
                            </td>
                            <td class="text-center" style="border: 1px solid;"><?= $model->code ?: 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="padding: 0 10px"><?= Yii::t("app", "Date and time") ?>:
                            </td>
                            <td class="text-center"
                                style="border: 1px solid;">
                                <?= !empty($model->created_at) ? TimeHelper::formatAsDateTime($model->created_at) : '' ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <table class="header" cellspacing="0">
        <thead>
        <tr>
            <th class="text-left" style="width: 50%">
                <h4><?= Yii::t("app", "Billing address") ?>
            </th>
            <th class="text-right">
                <h4><?= Yii::t("app", "Shipping address") ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td valign="top" style="padding-right: 25px">
                <?php if (isset($model->user)) : ?>
                    <?= $model->user->getFullName() ?><br>
                    <?= $model->user->address ?><br>
                    <?= $model->user->city ?>, <?= $model->user->zip ?><br>
                    <?= $model->user->phone ?>
                <?php endif; ?>
            </td>
            <td class="text-right" style="padding-left: 25px">
                <?= $model->getCustomerFullName() ?><br>
                <?= $model->delivery_address ?><br>
                <?= $model->delivery_city ?>, <?= $model->delivery_zip ?><br>
                <?= $model->delivery_phone ?>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="body" cellspacing="0">
        <thead>
        <tr>
            <th class="text-left" style="width: 15px;">
                <h4>#</h4>
            </th>
            <th class="text-left" style=" width: 230px;">
                <h4><?= Yii::t("app", "Product") ?></h4>
            </th>
            <th>
                <h4><?= Yii::t("app", "SKU") ?></h4>
            </th>
            <th>
                <h4><?= Yii::t("app", "Quantity") ?></h4>
            </th>
            <th>
                <h4><?= Yii::t("app", "Price") ?></h4>
            </th>
            <th class="text-right">
                <h4><?= Yii::t("app", "Total") ?></h4>
            </th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($model->orderItems as $key => $orderItem): ?>
            <?php $product = $orderItem->getProductWithDeleted() ?>
            <?php $productVariant = $orderItem->getProductVariantWithDeleted() ?>
            <tr class="item">
                <td><?= $key + 1 ?></td>
                <td>
                    <?= $product->name ?>
                    <?php if (!empty($productVariant)) : ?>
                        <small>(<?= $productVariant->name ?>)</small>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if (!empty($productVariant->sku)) : ?>
                        <?= $productVariant->sku ?>
                    <?php elseif (!empty($product->sku)) : ?>
                        <?= $product->sku ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= $orderItem->quantity ?></td>
                <td class="text-center price">
                    <?= PriceHelper::format($orderItem->price); ?>
                </td>
                <td class="text-right price">
                    <?= PriceHelper::format($orderItem->total); ?>
                </td>
            </tr>';
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6" style="border: unset; padding: 15px 0;"></td>
        </tr>
        <tr>
            <td colspan="3" style="border: unset;"></td>
            <td colspan="2"><h4><?= Yii::t("app", "Subtotal") ?></h4></td>
            <td class="text-right price">
                <?= PriceHelper::format($model->subtotal); ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border: unset;"></td>
            <td colspan="2"><h4><?= Yii::t("app", "Total Tax") ?></h4></td>
            <td class="text-right price">
                <?= PriceHelper::format($model->total_discount); ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border: unset;"></td>
            <td colspan="2"><h4><?= Yii::t("app", "Total") ?></h4></td>
            <td class="text-right price">
                <?= PriceHelper::format($model->total) ?>
            </td>
        </tr>
        </tfoot>
    </table>

    <table class="footer" cellspacing="0">
        <thead>
        <tr>
            <th class="text-left">
                <h4><?= Yii::t("app", "Delivery Notes") ?></h4>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td valign="top">
                <?= $model->delivery_notes ?>
            </td>
        </tr>
        </tbody>
    </table>

    <htmlpagefooter name="page-footer">
        <table width="100%" class="text-center">
            <tr>
                <td style="color: #848484">
                    <?= Yii::$app->name ?>, Copyright © 2025. All rights reserved.
                    <?= Yii::$app->params['contact']['email'] ?>
                </td>
            </tr>
            <tr>
                <td>{PAGENO} / {nb}</td>
            </tr>
        </table>
    </htmlpagefooter>
</div>
</body>
</html>
