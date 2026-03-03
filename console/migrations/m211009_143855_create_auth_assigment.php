<?php

use common\helpers\RbacHelper;
use yii\db\Migration;

/**
 * Class m211009_143855_create_auth_assigment
 */
class m211009_143855_create_auth_assigment extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        $auth->assign($auth->getRole(RbacHelper::ROLE_ADMIN), 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAllAssignments();
    }
}
