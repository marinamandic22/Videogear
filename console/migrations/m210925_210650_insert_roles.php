<?php

use common\helpers\RbacHelper;
use yii\db\Migration;

/**
 * Class m210925_210650_insert_roles
 */
class m210925_210650_insert_roles extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $adminRole = $auth->createRole(RbacHelper::ROLE_ADMIN);
        $adminRole->description = RbacHelper::getRoleLabel(RbacHelper::ROLE_ADMIN);

        $contentManagerRole = $auth->createRole(RbacHelper::ROLE_CONTENT_MANAGER);
        $contentManagerRole->description = RbacHelper::getRoleLabel(RbacHelper::ROLE_CONTENT_MANAGER);

        $auth->add($adminRole);
        $auth->add($contentManagerRole);
        $auth->addChild($adminRole, $contentManagerRole);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $adminRole = $auth->getRole(RbacHelper::ROLE_ADMIN);
        $contentManagerRole = $auth->getRole(RbacHelper::ROLE_CONTENT_MANAGER);

        $auth->removeChild($adminRole, $contentManagerRole);
        $auth->remove($contentManagerRole);
        $auth->remove($adminRole);
    }
}
