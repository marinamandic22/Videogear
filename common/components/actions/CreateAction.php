<?php

namespace common\components\actions;


use Yii;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use yii\web\Response;
use common\components\orm\ActiveRecord;
use yii\widgets\ActiveForm;

/**
 * Class CreateAction
 *
 * @property Model $model
 */
class CreateAction extends ItemAction
{
    /**
     * @var string the name of the view action.
     */
    public $view = 'create';

    public $modalView = 'create_modal';

    public $redirectUrl;

    public $model;

    /**
     * @var callable
     * The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     *     // $model is the requested model instance.
     *     return $this->redirect(['my-action', 'id' => $model->getPrimaryKey()]);
     * }
     * ```
     */
    public $afterSave;

    /**
     * @return mixed
     */
    public function run()
    {
        $this->checkAccess();

        /** @var ActiveRecord $model */
        $modelClass = $this->modelClass;
        $model = $modelClass::createObject(\Yii::$app->getRequest()->get());
        $this->model = $model;
        $model->setScenario($this->scenario);

        $this->controller->getView()->title = "Create new {$model->getPublicName()}";
        $i18nCategory = $model->getI18nCategory(\Yii::$app->language);

        if ($model->load(\Yii::$app->getRequest()->post())) {
            if ($this->beforeSave() && $model->save() && $this->afterSave()) {

                $message = $this->responseMessage ?: (
                $i18nCategory ?
                    Yii::t($i18nCategory, '{:model} successfully created!', [':model' => $model->getPublicName()]) :
                    Yii::t('app', '{:model} successfully created!', [':model' => $model->getPublicName()])
                );

                if (Yii::$app->request->getIsAjax()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    $returnMessage = [
                        'success' => true,
                        'message' => $message
                    ];

                    if (Yii::$app->request->get('returnAttributes', false)) {
                        $attributes = explode(',', Yii::$app->request->get('returnAttributes'));

                        foreach ($attributes as $attribute) {
                            if ($model->hasProperty($attribute)) {
                                $returnMessage['attributes'][$attribute] = $model->{$attribute};
                            } else if ($model->hasAttribute($attribute)) {
                                $returnMessage['attributes'][$attribute] = $model->getAttribute($attribute);
                            } else {
                                $returnMessage['attributes'][$attribute] = null;
                            }
                        }

                    }

                    if (!empty($this->afterSave)) {
                        call_user_func($this->afterSave, $model);
                    }

                    return $returnMessage;
                }

                \Yii::$app->getSession()->addFlash('success', $message);

                $afterSave = $this->afterSave;
                if (empty($afterSave)) {
                    $afterSave = function (BaseActiveRecord $model) {
                        return $this->controller->redirect(['update', 'id' => $model->getPrimaryKey()]);
                    };
                }

                return call_user_func($afterSave, $model);
            }

            $errorMessage = $i18nCategory ? Yii::t($i18nCategory, '{:model} could not be created!', [
                ':model' => $model->getPublicName()
            ]) : Yii::t('app', '{:model} could not be created!', [
                ':model' => $model->getPublicName()
            ]);

            $errorMessage .= '<br>' . implode('<br>', $model->getFirstErrors());

            if (Yii::$app->request->getIsAjax()) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ActiveForm::validate($model)
                ];
            }

            \Yii::$app->getSession()->addFlash('error', $errorMessage);
        }

        $params = $this->resolveParams(['model' => $model]);

        return $this->render($params);
    }

    /**
     * @return bool
     */
    protected function beforeSave(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function afterSave(): bool
    {
        return true;
    }

    private function render(array $params = [])
    {
        $view = Yii::$app->request->getIsAjax() ? $this->modalView : $this->view;

        return $this->controller->renderAjaxConditional($view, $params);
    }
}
