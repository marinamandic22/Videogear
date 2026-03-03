<?php

/**
 * @var Order $model
 */

use common\helpers\PriceHelper;
use common\helpers\TimeHelper;
use common\models\Order;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<div class="mj-column-per-100 outlook-group-fix"
     style="vertical-align:top;display:inline-block;direction:ltr;text-align:center;width:100%;"
>
    <table style="width: 100%;padding:0;text-align: left;border-collapse: collapse;">
        <tbody>
        <tr>
            <td valign="center"
                class="title"
                style="font-size: 27px; padding: 20px; background-color: #111111; color: white;"
            >
                <?= Yii::t("app", "You have a new order {code}", [
                    'code' => "#{$model->code}"
                ]) ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <?= Yii::t("app", "You’ve received the following order from {user}", [
                    'user' => isset($model->user) ? $model->user->getFullName() : ""
                ]) ?>
                :<br><br>
                <span style="font-size: 18px;" class="subtitle">
                        [<?= Html::a('Order #' . $model->code, Yii::$app->params['backendUrl'] . '/order/view?id=' . $model->id, [
                        'style' => 'color: black;'
                    ]) ?>]
                        <strong>(<?= $model->created_at ? TimeHelper::formatAsDate($model->created_at, 'F d, Y') : '' ?>)</strong>
                    </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px 20px 20px;">
                <table style="text-align: left; width: 100%; table-layout:fixed; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th style="width: 60%;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;" class="w-auto">
                            <?= Yii::t("app", "Product") ?>
                        </th>
                        <th style="word-break: break-word;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;width: 60px;text-align: right;">
                            <?= Yii::t("app", "Quantity") ?>
                        </th>
                        <th style="word-break: break-word;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;text-align: right;">
                            <?= Yii::t("app", "Price") ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($model->orderItems)) : ?>
                        <?php foreach ($model->orderItems as $orderItem) : ?>
                            <tr>
                                <td style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;">
                                        <span style="padding-bottom: 10px;">
                                            <?= $orderItem->product->name ?>
                                            <?php if (!empty($orderItem->productVariant)): ?>
                                                <small>(<?= $orderItem->productVariant->name ?>)</small>
                                            <?php endif; ?>
                                        </span>
                                </td>
                                <td style="word-break: break-word;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;text-align: right;">
                                    <?= $orderItem->quantity ?>
                                </td>
                                <td style="word-break: break-word;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;text-align: right;">
                                    <?= PriceHelper::format($orderItem->quantity * $orderItem->price) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                    <tfoot style="border-top: 3px solid #dedede;">
                    <tr>
                        <td colspan="2" style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;">
                            <?= Yii::t("app", "Shipping") ?>:
                        </td>
                        <td style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;text-align: right;">
                            <?= PriceHelper::format($model->shipping_cost) ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;">
                            <?= Yii::t("app", "Total") ?>:
                        </td>
                        <td style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;text-align: right;">
                            <?= PriceHelper::format($model->total) ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 20px 20px 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <?php if (!empty($model->delivery_notes)) : ?>
                        <tr>
                            <td valign="top" colspan="2">
                                    <span style="font-size: 18px;" class="subtitle">
                                        <strong>
                                            <?= Yii::t("app", "Delivery Notes") ?>:
                                        </strong>
                                        <br>
                                    </span>
                                <br>
                                <?= $model->delivery_notes ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 20px 20px 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="font-size: 18px;" class="subtitle">
                        <th style="padding: 20px 0 20px 0;">
                            <strong><?= Yii::t("app", "Billing address") ?></strong>
                        </th>
                        <th style="padding: 20px 0 20px 0;">
                            <strong><?= Yii::t("app", "Shipping address") ?></strong>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td valign="top"
                            style="width: 50%;border: 1px solid #dedede;border-collapse: collapse;padding: 15px;">
                            <?php if (isset($model->user)) : ?>
                                <?= $model->user->getFullName() ?><br>
                                <?= $model->user->address ?><br>
                                <?= $model->user->city ?>, <?= $model->user->zip ?><br>
                                <?= $model->user->phone ?>
                            <?php endif; ?>
                        </td>
                        <td valign="top" style="border: 1px solid #dedede;border-collapse: collapse;padding: 15px;">
                            <?= $model->getCustomerFullName() ?><br>
                            <?= $model->delivery_address ?><br>
                            <?= $model->delivery_city ?>, <?= $model->delivery_zip ?><br>
                            <?= $model->delivery_phone ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
