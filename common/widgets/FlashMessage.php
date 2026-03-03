<?php

namespace common\widgets;

use Yii;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class FlashMessage extends \yii\bootstrap4\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     */
    public $alertTypes = ['error', 'success', 'info', 'warning'];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            if (!in_array($type, $this->alertTypes)) {
                continue;
            }

            foreach ((array)$flash as $message) {
                $this->view->registerJs("main.ui.notify(`{$message}`, `{$type}`);");
            }

            $session->removeFlash($type);
        }
    }
}
