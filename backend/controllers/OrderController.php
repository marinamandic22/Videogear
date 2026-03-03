<?php

namespace backend\controllers;

use common\components\actions\SearchAction;
use common\components\actions\UpdateAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\helpers\PDFHelper;
use common\models\Order;
use common\models\search\OrderItemSearch;
use common\models\search\OrderSearch;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseController
{
    public $modelClass = Order::class;
    public $searchModelClass = OrderSearch::class;

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
                'modalView' => 'view',
                'params' => function ($action, Order $model) {
                    $searchModel = new OrderItemSearch(['order_id' => $model->id]);
                    return [
                        'model' => $model,
                        'orderItemsDataProvider' => $searchModel->search([])
                    ];
                }
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'scenario' => Order::SCENARIO_ORDER_UPDATE,
            ],
        ]);
    }

    public function actionUpdateStatus($id, $status)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        /* @var Order $model */
        $model = $this->findModel($id);

        $model->status = $status;

        if (!$model->save()) {
            return [
                'success' => false,
                'message' => 'Order status canno\'t be updated!<br>' . implode('<br>', $model->getFirstErrors()),
                'errors' => ActiveForm::validate($model)
            ];
        }

        return [
            'success' => true,
            'message' => 'Order status successfully updated!'
        ];
    }

    public function actionInvoice($id)
    {
        /* @var $model Order */
        $model = Order::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Page not found!');
        }

        $content = $this->renderPartial('partials/_invoice', [
            'model' => $model,
        ]);

        $fileName = "Invoice - {$model->code}.pdf";
        PDFHelper::generatePDF($content, $fileName);
    }
}
