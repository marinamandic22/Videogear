<?php

namespace common\helpers;

use common\models\User;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class RbacHelper
{
    const ROLE_ADMIN = 'admin';
    const ROLE_CONTENT_MANAGER = 'content_manager';

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_CONTENT_MANAGER => 'Content Manager'
    ];

    /**
     * @throws Exception
     */
    public static function getRoleLabel($roleName)
    {
        if (!$roleLabel = ArrayHelper::getValue(static::ROLES, $roleName, 'Customer')) {
            return Inflector::humanize($roleName);
        }
        return $roleLabel;
    }

    public static function getCustomerUsers() {
        return User::findAll(['NOT IN', 'id', self::getBackendUserIds()]);
    }

    public static function getBackendUsers() {
        return User::findAll(['IN', 'id', self::getBackendUserIds()]);
    }

    public static function getBackendUserIds() {
        $superAdminIds = Yii::$app->authManager->getUserIdsByRole(static::ROLE_ADMIN);
        $adminIds = Yii::$app->authManager->getUserIdsByRole(static::ROLE_CONTENT_MANAGER);

        return ArrayHelper::merge($adminIds, $superAdminIds);
    }
}
