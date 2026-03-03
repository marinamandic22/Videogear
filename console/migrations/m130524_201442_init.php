<?php

use common\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        echo "*** Creating main database structure for application. " . PHP_EOL;

        $query = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'initial.sql');

        $success = Yii::$app->db->createCommand($query)->execute();

        $this->insert('user', [
            'first_name' => 'Marina',
            'last_name' => 'Mandić',
            'username' => 'admin',
            'password_hash' => '$2y$13$uwbmE6LJ5qPG/HyCbC3KluEAD6geaDhNHeo5byon3EKIZcthQm5y2', //password1999
            'email' => 'marinamandic1999@yahoo.com',
            'phone' => '+38766638949',
            'status' => User::STATUS_ACTIVE,
            'address' => 'Njegoševa 37',
            'city' => 'Istočno Sarajevo',
            'country' => 'BA',
            'zip' => '71123',
            'is_staff' => 1,
            'created_at' => time()
        ]);

        return $success;
    }

    public function safeDown()
    {
        echo "m190123_101647_initial_database_structure cannot be reverted.\n";

        return false;
    }
}
