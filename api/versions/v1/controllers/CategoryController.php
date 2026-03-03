<?php


namespace api\versions\v1\controllers;

use api\components\actions\SearchAction;
use api\components\web\BaseApiController;
use common\models\ProductCategory;
use common\models\search\ProductCategorySearch;
use yii\helpers\ArrayHelper;

class CategoryController extends BaseApiController
{
    public $modelClass = ProductCategory::class;
    public $searchModelClass = ProductCategorySearch::class;

    public $guestActions = ['index', 'view', 'options'];

    public function accessRules()
    {
        return ArrayHelper::merge(parent::accessRules(), [
            [
                'actions' => ['index', 'view', 'options'],
                'allow' => true
            ]
        ]);
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => SearchAction::class,
                'modelClass' => $this->searchModelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scope' => function(ProductCategorySearch $searchModel) {
                    $searchModel->is_active = ProductCategory::STATUS_ACTIVE;
                    $searchModel->onlyTopLevel = true;
                }
            ],
        ]);
    }
}
