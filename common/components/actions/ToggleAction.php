<?php

namespace common\components\actions;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ToggleAction extends ItemAction
{
    /**
     * @var string the class name of the model.
     */
    public $modelClass;

    /**
     * @var string  Model attribute name
     */
    public $attribute = 'is_active';

    /**
     * @var mixed   Active state value to be inserted
     */
    public $onValue = 1;

    /**
     * @var mixed   Inactive state value to be inserted
     */
    public $offValue = 0;

    /**
     * @var string   Action label for active state in flash message
     */
    public $onActionLabel;

    /**
     * @var string   Action label for inactive state in flash message
     */
    public $offActionLabel;

    /**
     * @var string scenario for this action
     **/
    public $scenario = 'default';

    /**
     * @param null $id
     * @return array
     * @throws BadRequestHttpException|NotFoundHttpException
     */
    public function run($id = null)
    {
        if (Yii::$app->request->isAjax) {

            $model = $this->findModel($id);
            $model->setScenario($this->scenario);
            Yii::$app->response->format = Response::FORMAT_JSON;
            $i18nCategory = $model->getI18nCategory(\Yii::$app->language);

            if ($model->{$this->attribute} === $this->offValue) {
                $model->{$this->attribute} = $this->onValue;
                $actionLabel = $this->onActionLabel ?: (
                $i18nCategory ?
                    Yii::t($i18nCategory, 'activated') :
                    Yii::t('app', 'activated')
                );
            } else {
                $model->{$this->attribute} = $this->offValue;
                $actionLabel = $this->offActionLabel ?: (
                $i18nCategory ?
                    Yii::t($i18nCategory, 'deactivated') :
                    Yii::t('app', 'deactivated')
                );
            }

            if ($model->save(false, [$this->attribute])) {
                $message = $i18nCategory ? Yii::t($i18nCategory, '{:model} successfully {:action}!', [
                    ':model' => $model->getPublicName(),
                    ':action' => $actionLabel
                ]) : Yii::t('app', '{:model} successfully {:action}!', [
                    ':model' => $model->getPublicName(),
                    ':action' => $actionLabel
                ]);

                return [
                    'success' => true,
                    'message' => $message
                ];
            }

            $errorMessage = $i18nCategory ? Yii::t($i18nCategory, '{:model} canno\'t be {:action}!', [
                ':model' => $model->getPublicName(),
                ':action' => $actionLabel
            ]) : Yii::t('app', '{:model} canno\'t be {:action}!', [
                ':model' => $model->getPublicName(),
                ':action' => $actionLabel
            ]);

            $errorMessage .= '<br>' . implode('<br>', $model->getFirstErrors());

            return [
                'success' => false,
                'message' => $errorMessage,
                'errors' => ActiveForm::validate($model)
            ];
        }
        throw new BadRequestHttpException(Yii::t('app', 'Invalid request'));
    }
}
