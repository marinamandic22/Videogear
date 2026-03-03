<?php


namespace common\helpers;

use Mpdf\Mpdf;
use Yii;
use yii\helpers\ArrayHelper;

class PDFHelper
{
    public static function generatePDF($content, $fileName, $outputDest = 'I', array $options = [])
    {
        $defaultOptions = [
            'tempDir' => Yii::getAlias("@backend") . '/runtime/mpdf',
            'setAutoTopMargin' => 'stretch',
            'setAutoBottomMargin' => 'stretch',
            'showWatermarkImage' => true,
            'mode' => 'utf-8',
            'format' => 'A4'
        ];

        if (!empty($options)) {
            $defaultOptions = ArrayHelper::merge($defaultOptions, $options);
        }

        $mpdf = new mPDF($defaultOptions);
        $mpdf->WriteHTML($content);
        $mpdf->Output($fileName, $outputDest);

        return $mpdf;
    }
}
