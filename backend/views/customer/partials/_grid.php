<?php

use common\helpers\BaseHelper;
use common\helpers\RbacHelper;
use common\models\User;
use common\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $pjaxId string */
/* @var $gridId string */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?= GridView::widget([
    'id' => User::INDEX_GRID_ID,
    'dataProvider' => $dataProvider,
    'enableAdd' => false,
    'addButtonOption' => [
        'content' => '<i class="fa fa-user fa-lg mr-3"></i>' . Yii::t('app', 'Add New User')
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => Yii::t('app', 'Customer'),
            'attribute' => 'user',
            'format' => 'raw',
            'value' => function (User $model) {
                return $this->render(Url::to(['shared/partials/_avatar']), [
                    'model' => $model,
                    'controllerId' => 'customer'
                ]);
            }
        ],
        [
            'label' => Yii::t('app', 'Contact Details'),
            'attribute' => 'contact',
            'format' => 'raw',
            'value' => function (User $model) {
                $emailRowContent = "<i class='fa fa-envelope mr-2'></i><a href='mailto:{$model->email}'>{$model->email}</a>";
                $emailRow = !empty($model->email) ? Html::tag('div', $emailRowContent, ['class' => 'mb-1']) : "";

                $phoneRowContent = "<i class='fa fa-phone mr-2'></i><a href='tel:{$model->phone}'>{$model->phone}</a>";
                $phoneRow = !empty($model->email) ? Html::tag('div', $phoneRowContent) : "";

                return "{$emailRow}{$phoneRow}";
            }
        ],
        [
            'label' => Yii::t('app', 'Location'),
            'attribute' => 'location',
            'format' => 'raw',
            'value' => function (User $model) {
                $addressRow = !empty($model->address) ? "<div class='mb-1'><strong>{$model->address}</strong></div>" : "";
                $cityCountryZipRow = BaseHelper::formatToCharSeparatedString([$model->city, $model->country, $model->zip]);

                $cityCountryZipRow = !empty($cityCountryZipRow) ? Html::tag('div', $cityCountryZipRow) : '';
                return "{$addressRow}{$cityCountryZipRow}";
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '<div class="d-flex justify-content-end">{update}</div>',
            'buttons' => [
                'update' => function ($url, User $model) {
                    if (!Yii::$app->user->can(RbacHelper::ROLE_ADMIN)) {
                        return null;
                    }

                    $url = Url::to(['user/update', 'id' => $model->id]);

                    return Html::tag('span', '<i class="fa fa-wrench"></i>', [
                        'data-href' => $url,
                        'data-size' => 'modal-lg',
                        'class' => 'btn btn-sm btn-round btn-white btn-just-icon btn-loading btn-modal-control',
                        'title' => Yii::t('app', 'Update')
                    ]);
                },
            ],
        ],
    ],
]); ?>
