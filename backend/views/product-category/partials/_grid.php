<?php

use common\components\image\ImageSpecification;
use common\models\ProductCategory;
use common\widgets\grid\TreeGrid;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $gridId string */
/* @var $dataProvider ActiveDataProvider */
/* @var $pjaxId string */

?>

<?= TreeGrid::widget([
    'id' => $gridId,
    'dataProvider' => $dataProvider,
    'keyColumnName' => 'id',
    'parentColumnName' => 'parent_category_id',
    'parentColumnWithAlias' => 'product_category.parent_category_id',
    'collapsable' => true,
    'enableAdd' => true,
    'addButtonOption' => [
        'content' => '<i class="fa fa-list fa-lg mr-3"></i>' . Yii::t('app', 'Add New Category')
    ],
    'columns' => [
        [
            'label' => Yii::t("app", "Category"),
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function (ProductCategory $model) {
                if(!$model->cover_image_id) {
                    return $model->name;
                }

                $src = Url::to(['/image/view', 'id' => $model->cover_image_id, 'spec' => ImageSpecification::THUMB_EXTRA_SMALL_SQUARED]);
                $img = Html::img($src, [
                    'class' => 'thumb thumb-xs mr-4',
                    'alt' => $model->name
                ]);

                return "{$img} {$model->name}";
            },
            'contentOptions' => ['class' => 'text-nowrap', 'style' => 'width:90%;']
        ],
        [
            'label' => Yii::t('app', 'Active'),
            'attribute' => 'active',
            'format' => 'raw',
            'contentOptions' => ['style' => 'width:10%;'],
            'value' => function (ProductCategory $model) use ($pjaxId) {
                $input = Html::activeInput('checkbox', $model, "[{$model->id}]is_active", [
                    'checked' => $model->is_active === ProductCategory::STATUS_ACTIVE
                ]);
                $label = Html::label('', Html::getInputId($model, "[{$model->id}]is_active"));

                $content =
                    "<div class='toggle-switch-wrap'>
                            <span class='toggle-switch toggle-switch-reverse'>
                                {$input}
                                {$label}
                            </span>
                        </div>";

                $action = $model->is_active === ProductCategory::STATUS_ACTIVE ?
                    Yii::t('app', 'deactivate') :
                    Yii::t('app', 'activate');

                return Html::tag('span', $content, [
                    'class' => 'btn-control-confirm',
                    'data-msg' => Yii::t('app', "Are you sure you want to {:action} category: {:category}?", [
                        ':action' => $action,
                        ':category' => $model->name
                    ]),
                    'data-url' => Url::to(['product-category/toggle-status', 'id' => $model->id]),
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
                'update' => function ($url, ProductCategory $model) {
                    $url = Url::to(['/product-category/update', 'id' => $model->id]);

                    return Html::tag('span', '<i class="fa fa-wrench"></i>', [
                        'data-href' => $url,
                        'data-size' => 'modal-lg',
                        'class' => 'btn btn-sm btn-round btn-white btn-just-icon btn-loading btn-modal-control mr-2',
                        'title' => Yii::t('app', 'Update')
                    ]);
                },
                'delete' => function ($url, ProductCategory $model) use ($pjaxId) {
                    $url = Url::to(['/product-category/delete', 'id' => $model->id]);
                    $msg = Yii::t('app', 'Are you sure you want to delete category: {:name}', [':name' => $model->name]);

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
    ]
]); ?>
