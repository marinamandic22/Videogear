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
    'enableAdd' => true,
    'addButtonOption' => [
        'content' => '<i class="fa fa-user fa-lg mr-3"></i>' . Yii::t('app', 'Add New User')
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => Yii::t('app', 'User'),
            'attribute' => 'user',
            'format' => 'raw',
            'value' => function (User $model) {
                return $this->render(Url::to(['shared/partials/_avatar']), [
                    'model' => $model,
                    'controllerId' => 'user'
                ]);
            }
        ],
        [
            'label' => Yii::t('app', 'Role'),
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function (User $model) {
                // Get role name or default to 'Customer'
                $roles = Yii::$app->authManager->getRolesByUser($model->id);
                $roleName = !empty($roles) ? RbacHelper::getRoleLabel(array_key_first($roles)) : 'Customer';

                if (!empty($roles)) {
                    $roleKey = array_key_first($roles);

                    $badgeClass = match ($roleKey) {
                        RbacHelper::ROLE_ADMIN => 'badge-violet',
                        RbacHelper::ROLE_CONTENT_MANAGER => 'badge-info',
                        default => 'badge-success',
                    };
                } else {
                    $badgeClass = 'badge-light';
                }

                return Html::tag('span', $roleName, ['class' => "badge {$badgeClass}"]);
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
            'label' => Yii::t('app', 'Active'),
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function (User $model) use ($pjaxId) {
                $isDisabled = Yii::$app->user->id == $model->id;
                $input = Html::activeInput('checkbox', $model, "[{$model->id}]status", [
                    'checked' => $model->status === User::STATUS_ACTIVE,
                    'disabled' => $isDisabled
                ]);
                $label = Html::label('', Html::getInputId($model, "[{$model->id}]status"));

                $content =
                    "<div class='toggle-switch-wrap' " . ($isDisabled ? 'disabled' : '') . ">
                        <span class='toggle-switch toggle-switch-reverse'>
                            {$input}
                            {$label}
                        </span>
                    </div>";

                $action = $model->status === User::STATUS_ACTIVE ?
                    Yii::t('app', 'deactivate') :
                    Yii::t('app', 'activate');

                return !$isDisabled ? Html::tag('span', $content, [
                    'class' => 'btn-control-confirm',
                    'data-msg' => Yii::t('app', "Are you sure you want to {:action} user: {:user}?", [
                        ':action' => $action,
                        ':user' => $model->getFullName()
                    ]),
                    'data-url' => Url::to(['user/toggle-status', 'id' => $model->id]),
                    'data-json-response' => 1,
                    'data-loader' => 0,
                    'data-pjax-id' => $pjaxId
                ]) : Html::tag('div', $content);
            }],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '<div class="d-flex justify-content-end">{update}{delete}</div>',
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
                'delete' => function ($url, User $model) use ($pjaxId) {
                    $isDisabled = Yii::$app->user->id == $model->id;
                    if ($isDisabled || !Yii::$app->user->can(RbacHelper::ROLE_ADMIN)) {
                        return null;
                    }

                    $url = Url::to(['/user/delete', 'id' => $model->id]);
                    $msg = Yii::t('app', 'Are you sure you want to delete user: {:name}', [
                        ':name' => $model->getFullName()
                    ]);
                    return Html::tag('span', '<i class="fa fa-trash"></i>', [
                        'data-href' => $url,
                        'data-confirm-msg' => $msg,
                        'data-pjax-id' => $pjaxId,
                        'data-type' => 'post',
                        'class' => 'btn btn-sm btn-round btn-white btn-just-icon btn-control-pjax-action ml-2',
                        'title' => Yii::t('app', 'Delete')
                    ]);
                },
            ],
        ],
    ],
]); ?>
