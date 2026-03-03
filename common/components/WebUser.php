<?php

namespace common\components;

use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * Class WebUser
 * @package common\components
 *
 * @property User $identity
 */
class WebUser extends \yii\web\User
{
    protected $fullName;

    /**
     * @throws \Exception
     */
    public function getFullName()
    {
        if (empty($this->fullName)) {
                $this->fullName = ArrayHelper::getValue($this, 'identity.fullName');
        }

        return $this->fullName;
    }
}
