<?php

namespace common\components\controllers;

use common\helpers\RbacHelper;
use common\components\orm\ActiveRecord;
use Yii;
use yii\filters\VerbFilter;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * Class BaseController
 * @package common\components\controllers
 *
 * @property string $modelClass
 *
 */
class BaseController extends \yii\web\Controller
{
    /** @var string */
    public $modelClass;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [RbacHelper::ROLE_CONTENT_MANAGER],
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
                'view' => '@app/views/site/error',
                'layout' => 'blank'
            ],
        ];
    }

    /**
     * Render view depending weather it is ajax request or not
     *
     * @param $view
     * @param array $params
     * @return string
     */
    public function renderAjaxConditional($view, array $params = [])
    {
        return Yii::$app->request->getIsAjax() ? $this->renderAjax($view, $params) : $this->render($view, $params);
    }


    /**
     * Finds the CommissionRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        /** @var $model ActiveRecord */
        if (($model = $modelClass::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
