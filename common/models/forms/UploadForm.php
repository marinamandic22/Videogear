<?php

namespace common\models\forms;


use common\models\File;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class UploadForm extends Model
{
    public $files;
    public $modelClass;
    public $responseData = [];

    public function rules()
    {
        return [
            [['modelClass', 'files'], 'required'],
            [['folder_id', 'description', 'tagNames', 'id', 'files', 'storageType', 'clientId'], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->responseData = [];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($this->files as $file) {
                /** @var File $model */
                $model = new $this->modelClass;
                $model->file = $file;

                if (!$model->save()) {
                    throw new Exception(Yii::t('app', 'Could not save file. Errors: {:errors}', [
                        ':errors' => implode('<br>', $model->getFirstErrors()),
                    ]));
                }

                $this->responseData[] = ['file_id' => $model->id, 'image_id' => $model->image_id];
            }
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addError('files', $e->getMessage());
        }

        return false;
    }

}
