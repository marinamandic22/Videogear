<?php

namespace backend\controllers;

use common\components\actions\CreateAction;
use common\components\actions\DeleteAction;
use common\components\actions\SearchAction;
use common\components\actions\ToggleAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\helpers\RbacHelper;
use common\models\forms\ChangePasswordForm;
use common\models\forms\RegistrationForm;
use common\models\search\CustomerSearch;
use common\models\search\OrderSearch;
use common\models\User;
use common\models\search\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class CustomerController extends BaseController
{
    public $modelClass = User::class;
    public $searchModelClass = CustomerSearch::class;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => [RbacHelper::ROLE_CONTENT_MANAGER],
                        ],
                        [
                            'allow' => true,
                            'roles' => [RbacHelper::ROLE_ADMIN],
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ];
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'class' => SearchAction::class,
                'searchModel' => $this->searchModelClass,
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'params' => function ($action, User $model) {
                    $orderSearchModel = new OrderSearch(['user_id' => $model->id]);

                    $orderDataProvider = $orderSearchModel->search(\Yii::$app->request->queryParams);
                    $orderDataProvider->pagination->pageSize = 5;

                    return [
                        'orderDataProvider' => $orderDataProvider
                    ];
                },
                'modalView' => 'view'
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => RegistrationForm::class,
                'scenario' => RegistrationForm::SCENARIO_ADMIN_UPDATE,
                'findModel' => function ($id) {
                    return RegistrationForm::findOne($id);
                }
            ],
        ]);
    }
}
