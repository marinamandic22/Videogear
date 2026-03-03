<?php


namespace api\versions\v1\controllers;

use api\components\actions\CreateAction;
use api\components\web\BaseApiController;
use api\models\OrderForm;
use common\models\Order;
use common\models\search\OrderSearch;
use yii\helpers\ArrayHelper;
use yii\rest\OptionsAction;

class OrderController extends BaseApiController
{
    public $modelClass = OrderForm::class;
    public $searchModelClass = OrderSearch::class;

    public $guestActions = ['create', 'options'];

    public function accessRules()
    {
        return ArrayHelper::merge(parent::accessRules(), [
            [
                'actions' => ['create', 'options'],
                'allow' => true
            ]
        ]);
    }
}
