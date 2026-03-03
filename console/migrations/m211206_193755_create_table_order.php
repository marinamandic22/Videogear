<?php

use yii\db\Migration;

/**
 * Class m211206_193755_create_table_order
 */
class m211206_193755_create_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'code' => $this->string(45),
            'subtotal' => $this->decimal(10, 2),
            'total_tax' => $this->decimal(10, 2),
            'total_discount' => $this->decimal(10, 2),
            'shipping_cost' => $this->decimal(10, 2),
            'total' => $this->decimal(10, 2),
            'currency' => $this->string(3),
            'status' => $this->tinyInteger(),
            'delivery_first_name' => $this->string(255),
            'delivery_last_name' => $this->string(255),
            'delivery_address' => $this->string(255),
            'delivery_city' => $this->string(255),
            'delivery_zip' => $this->string(255),
            'delivery_country' => $this->string(255),
            'delivery_phone' => $this->string(45),
            'delivery_notes' => $this->string(255),
            'customer_ip_address' => $this->string(255),
            'customer_user_agent' => $this->string(255),
            'request' => $this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order');
    }
}
