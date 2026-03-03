<?php

namespace api\versions\v1\controllers;

use api\components\responses\ErrorResponse;
use api\components\responses\SuccessResponse;
use api\components\web\BaseApiController;
use common\components\WebUser;
use common\models\forms\RegistrationForm;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\OptionsAction;
use api\components\actions\CreateAction;
use yii\web\ServerErrorHttpException;

class UserController extends BaseApiController
{
    public $modelClass = \api\models\User::class;

    public $guestActions = ['register'];
    /**
     * @return array the access rules
     */
    public function accessRules()
    {
        return ArrayHelper::merge(parent::accessRules(), [
            [
                'actions' => ['update', 'info', 'options'],
                'allow' => '@',
            ],
            [
                'actions' => ['register'],
                'allow' => true
            ]
        ]);
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::class
            ],
            'register' => [
                'class' => CreateAction::class,
                'modelClass' => RegistrationForm::class,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => RegistrationForm::SCENARIO_CUSTOMER_REGISTRATION
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function actionInfo()
    {
        /** @var WebUser $user */
        $user = Yii::$app->getUser();

        /** @var User $identity */

        return $user->getIdentity();
    }

    /**
     * @throws \Throwable
     * @throws ServerErrorHttpException
     */
    public function actionUpdate() {
        /** @var \api\models\User $user */

        $user = Yii::$app->user->getIdentity();

        $user->load(Yii::$app->getRequest()->getBodyParams(), '');
        if($user->save()) {
            return (new SuccessResponse($user))->asArray();
        } elseif (!$user->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return (new ErrorResponse($user->getFirstErrors()))->asArray();
    }
}
