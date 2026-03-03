<?php

namespace backend\controllers;

use backend\models\forms\ProductForm;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\ToggleAction;
use common\components\actions\UpdateAction;
use common\components\controllers\BaseController;
use common\components\orm\ActiveRecord;
use common\models\Product;
use common\models\search\ProductSearch;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BaseController
{
    public $modelClass = Product::class;
    public $searchModelClass = ProductSearch::class;

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => SearchAction::class,
                'searchModel' => $this->searchModelClass,
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => ProductForm::class,
                'scenario' => ActiveRecord::SCENARIO_CREATE
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => ProductForm::class,
                'scenario' => ActiveRecord::SCENARIO_UPDATE,
            ],
            'toggle-status' => [
                'class' => ToggleAction::class,
                'modelClass' => $this->modelClass,
                'onValue' => Product::STATUS_ACTIVE,
                'offValue' => Product::STATUS_INACTIVE
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
            ],
        ]);
    }
}
