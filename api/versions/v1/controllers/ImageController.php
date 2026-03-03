<?php


namespace api\versions\v1\controllers;

use api\components\actions\ThumbAction;
use api\components\web\BaseApiController;
use common\models\Image;
use yii\helpers\ArrayHelper;

class ImageController extends BaseApiController
{
    public $modelClass = Image::class;

    public $guestActions = ['thumb', 'options'];
    public function accessRules()
    {
        return ArrayHelper::merge(parent::accessRules(), [
            [
                'actions' => ['thumb', 'options'],
                'allow' => true
            ]
        ]);
    }

    public function actions()
    {
        return [
            'thumb' => [
                'class' => ThumbAction::class,
                'modelClass' => $this->modelClass
            ]
        ];
    }

}
