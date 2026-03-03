<?php

namespace common\components\actions;

use Yii;
use yii\web\Response;
use yii\helpers\StringHelper;
use common\components\orm\ActiveRecord;

/**
 * Class DeleteAction
 *
 * @author 2amigOS! <http://2amigos.us/>
 */
class DeleteAction extends ItemAction
{
    /**
     * @var callable
     * The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     *     // $model is the requested model instance.
     *     return $this->redirect(['my-action');
     * }
     * ```
     */
    public $afterDelete;

    /**
     * If is true response format is set to json
     */
    public $ajaxResponse = true;

    /**
     * Used if ajaxResponse is true
     */
    public $responseMessage;

    /**
     * @param integer $id
     * @return mixed
     * @throws \Exception
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);
        $this->checkAccess($model);

        if (!$model->delete()) {
            throw new \Exception(implode('', $model->getFirstErrors()));
        }

        if (!empty($this->afterDelete)) {
            return call_user_func($this->afterDelete, $model);
        }

        if ($this->ajaxResponse) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $i18nCategory = $model->getI18nCategory(\Yii::$app->language);

            return [
                'success' => true,
                'message' => $this->responseMessage ?: ($i18nCategory ?
                    Yii::t($i18nCategory, '{:model} successfully deleted!', [
                        ':model' => $model->getPublicName()
                    ]) :
                    Yii::t('app', '{:model} successfully deleted!', [
                        ':model' => $model->getPublicName()
                    ])
                )
            ];
        }

        return $this->controller->redirect(['index']);
    }
}
