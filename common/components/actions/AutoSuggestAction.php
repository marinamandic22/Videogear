<?php

namespace common\components\actions;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AutoSuggestAction extends Action
{
    public $searchModel = null;
    public $perPage = 5;

    /**
     * @return array[]
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $searchClass = $this->searchModel;

        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        if (empty($searchClass)) {
            throw new InvalidConfigException('Action must have searchClass property defined.');
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $searchModel = new $searchClass();

        $queryParams = ArrayHelper::merge([
            'per-page' => $this->perPage,
            'page' => 1
        ], \Yii::$app->request->queryParams);

        $dataProvider = $searchModel->search($queryParams);

        $results = [];
        foreach ($dataProvider->getModels() as $model) {
            $results[] = ['id' => $model->id, 'text' => $model->name];
        }

        return [
            'results' => $results
        ];
    }
}