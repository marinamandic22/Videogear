<?php

use yii\db\Migration;

/**
 * Class m211211_201851_add_column_user_id_to_order_table
 */
class m211211_201851_add_column_user_id_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'user_id', $this->integer()->after('id'));
        $this->addForeignKey('fk_order_user', 'order', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_user', 'order');
        $this->dropColumn('order', 'user_id');
    }
}
