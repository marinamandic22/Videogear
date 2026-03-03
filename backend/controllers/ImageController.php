<?php

namespace backend\controllers;

use common\components\controllers\BaseController;
use common\components\image\ImageSpecification;
use common\helpers\TimeHelper;
use common\models\Image;
use Yii;
use yii\web\Response;

class ImageController extends BaseController
{
    public $modelClass = Image::class;

    public function actionView($id, $spec = ImageSpecification::THUMB_MEDIUM)
    {
        ini_set('memory_limit','256M');

        /** @var Image $image */
        $image = $this->findModel($id);

        $imageSpec = new ImageSpecification($spec);

        $thumb = $image->getThumb($imageSpec->getKey());

        return $this->renderImageResponse(
            $thumb->getETag(),
            $thumb->getLastModified(),
            $image->mime_type,
            $thumb->getUnsignedUrl()
        );
    }

    protected function renderImageResponse($eTag, $lastModified, $mimeType, $storageUrl)
    {
        $expireTime = TimeHelper::YEAR_SECONDS;
        $this->setResponseHeaderCache($expireTime, $eTag, $lastModified);

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $mimeType);

        return $this->redirect($storageUrl);
    }

    protected function setResponseHeaderCache($expireTime, $eTag, $lastModified)
    {
        Yii::$app->response->headers->add('Expires', gmdate('D, j M Y H:i:s T', time() + $expireTime));
        Yii::$app->response->headers->add('ETag', $eTag);
        Yii::$app->response->headers->add('Cache-Control', "max-age={$expireTime}, must-revalidate");
        Yii::$app->response->headers->add('Last-Modified', gmdate('D, j M Y H:i:s', $lastModified) . ' GMT');
    }

}
