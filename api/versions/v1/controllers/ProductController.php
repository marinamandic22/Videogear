<?php

namespace api\versions\v1\controllers;

use api\components\actions\SearchAction;
use api\components\web\BaseApiController;
use common\models\Product;
use common\models\search\ProductSearch;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\rest\ViewAction;

class ProductController extends BaseApiController
{
    public $modelClass = Product::class;
    public string $searchModelClass = ProductSearch::class;

    public $guestActions = ['index', 'view', 'options'];

    public function accessRules(): array
    {
        return ArrayHelper::merge(parent::accessRules(), [
            [
                'actions' => ['index', 'view', 'options'],
                'allow' => true
            ]
        ]);
    }

    public function actions(): array
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => SearchAction::class,
                'modelClass' => $this->searchModelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scope' => function (ProductSearch $searchModel, $_, $params) {
                    $searchModel->is_active = Product::STATUS_ACTIVE;

                    return $params;
                }
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'findModel' => [$this->modelClass, 'findOneBySlug']
            ],
        ]);
    }

    /**
     * @throws InvalidConfigException
     */
    protected function serializeData($data)
    {
        return Yii::createObject(['class' => 'yii\rest\Serializer', 'collectionEnvelope' => 'items'])->serialize($data);
    }
}
