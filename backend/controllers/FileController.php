<?php

namespace backend\controllers;

use common\components\actions\UploadAction;
use common\components\controllers\BaseController;
use common\models\File;


class FileController extends BaseController
{
    public $modelClass = File::class;

    public function actions()
    {
        return array_merge(parent::actions(), [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => $this->modelClass,
            ],
        ]);
    }
}