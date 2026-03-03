<?php

namespace common\components\actions;

use common\models\forms\UploadForm;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    public $modelClass;

    public function run($id = null)
    {
        ini_set('max_execution_time', '300');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new UploadForm();

        $model->load(Yii::$app->request->post());
        $model->modelClass = $this->modelClass;
        $model->files = UploadedFile::getInstances($model, 'files');

        if ($model->save()) {
            return [
                'success' => true,
                'message' => Yii::t('app', 'Files uploaded successfully.'),
                'data' => $model->responseData
            ];
        }

        return [
            'success' => false,
            'message' => Yii::t('app', 'Could not save file. Errors: {:errors}', [
                ':errors' => implode('<br>', $model->getFirstErrors())
            ])
        ];
    }
}
