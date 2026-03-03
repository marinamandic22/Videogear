<?php

use common\components\image\ImageSpecification;
use common\helpers\PriceHelper;
use common\helpers\RbacHelper;
use common\models\Product;
use common\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $pjaxId string */
/* @var $gridId string */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?= GridView::widget([
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'enableAdd' => true,
    'addButtonOption' => [
        'content' => '<i class="fa fa-cube fa-lg mr-3"></i>' . Yii::t('app', 'Add New Product'),
        'link' => true,
        'data-pjax' => 0,
        'class' => 'btn btn-success'
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => Yii::t('app', 'Product'),
            'attribute' => 'product',
            'format' => 'raw',
            'value' => function (Product $model) {
                if (!$model->cover_image_id) {
                    return $model->name;
                }

                $src = Url::to(['/image/view', 'id' => $model->cover_image_id, 'spec' => ImageSpecification::THUMB_EXTRA_SMALL_SQUARED]);
                $img = Html::img($src, [
                    'class' => 'thumb thumb-xs mr-4',
                    'alt' => $model->name
                ]);

                return "{$img} {$model->name}";
            },
            'contentOptions' => ['class' => 'text-nowrap']
        ],
        [
            'label' => Yii::t('app', 'Category'),
            'attribute' => 'category',
            'value' => function (Product $model) {
                return !empty($model->category) ? $model->category->name : null;
            }
        ],
//        'sku',
//        'quantity',
        [
            'attribute' => 'price',
            'value' => function (Product $model) {
                return PriceHelper::format($model->price);
            }
        ],
        [
            'label' => Yii::t('app', 'Active'),
            'attribute' => 'active',
            'format' => 'raw',
            'value' => function (Product $model) use ($pjaxId) {
                $input = Html::activeInput('checkbox', $model, "[{$model->id}]is_active", [
                    'checked' => $model->is_active === Product::STATUS_ACTIVE
                ]);
                $label = Html::label('', Html::getInputId($model, "[{$model->id}]is_active"));

                $content =
                    "<div class='toggle-switch-wrap'>
                            <span class='toggle-switch toggle-switch-reverse'>
                                {$input}
                                {$label}
                            </span>
                        </div>";

                $action = $model->is_active === Product::STATUS_ACTIVE ?
                    Yii::t('app', 'deactivate') :
                    Yii::t('app', 'activate');

                return Html::tag('span', $content, [
                    'class' => 'btn-control-confirm',
                    'data-msg' => Yii::t('app', "Are you sure you want to {:action} product: {:product}?", [
                        ':action' => $action,
                        ':product' => $model->name
                    ]),
                    'data-url' => Url::to(['product/toggle-status', 'id' => $model->id]),
                    'data-json-response' => 1,
                    'data-loader' => 0,
                    'data-pjax-id' => $pjaxId
                ]);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '<div class="d-flex justify-content-end">{update}{delete}</div>',
            'buttons' => [
                'update' => function ($url, Product $model) {
                    if (!Yii::$app->user->can(RbacHelper::ROLE_ADMIN)) {
                        return null;
                    }

                    $url = Url::to(['product/update', 'id' => $model->id]);

                    return Html::a('<i class="fa fa-wrench"></i>', $url, [
                        'data-pjax' => 0,
                        'class' => 'btn btn-sm btn-round btn-white btn-just-icon mr-2',
                        'title' => Yii::t('app', 'Update')
                    ]);
                },
                'delete' => function ($url, Product $model) use ($pjaxId) {
                    if (!Yii::$app->user->can(RbacHelper::ROLE_ADMIN)) {
                        return null;
                    }

                    $url = Url::to(['/product/delete', 'id' => $model->id]);
                    $msg = Yii::t('app', 'Are you sure you want to delete product: {:name}', [
                        ':name' => $model->name
                    ]);
                    return Html::tag('span', '<i class="fa fa-trash"></i>', [
                        'data-href' => $url,
                        'data-confirm-msg' => $msg,
                        'data-pjax-id' => $pjaxId,
                        'data-type' => 'post',
                        'class' => 'btn btn-sm btn-round btn-white btn-just-icon btn-control-pjax-action',
                        'title' => Yii::t('app', 'Delete')
                    ]);
                },
            ],
        ],
    ],
]); ?>
