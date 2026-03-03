<?php

namespace backend\controllers;

use common\components\actions\AutoSuggestAction;
use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\ToggleAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\components\orm\ActiveRecord;
use common\models\forms\RegistrationForm;
use common\models\Product;
use common\models\ProductCategory;
use common\models\search\ProductCategorySearch;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProductCategoryController implements the CRUD actions for ProductCategory model.
 */
class ProductCategoryController extends BaseController
{
    public $modelClass = ProductCategory::class;
    public $searchModelClass = ProductCategorySearch::class;

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => SearchAction::class,
                'searchModel' => $this->searchModelClass,
            ],
            'suggest' => [
                'class' => AutoSuggestAction::class,
                'searchModel' => $this->searchModelClass,
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'scenario' => ActiveRecord::SCENARIO_CREATE,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'scenario' => ActiveRecord::SCENARIO_UPDATE,
            ],
            'toggle-status' => [
                'class' => ToggleAction::class,
                'modelClass' => $this->modelClass,
                'onValue' => Product::STATUS_ACTIVE,
                'offValue' => Product::STATUS_INACTIVE,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
            ],
        ]);
    }
}
