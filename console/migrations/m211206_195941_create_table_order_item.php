<?php

use yii\db\Migration;

/**
 * Class m211206_195941_create_table_order_item
 */
class m211206_195941_create_table_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_item', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'product_variant_id' => $this->integer(),
            'quantity' => $this->integer(),
            'price' => $this->decimal(10, 2),
            'total' => $this->decimal(10, 2),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ]);

        $this->addForeignKey('fk_order_item_order', 'order_item', 'order_id', 'order', 'id');
        $this->addForeignKey('fk_order_item_product', 'order_item', 'product_id', 'product', 'id');
        $this->addForeignKey('fk_order_item_product_variant', 'order_item', 'product_variant_id', 'product_variant', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_order_item_order', 'order_item');
        $this->dropForeignKey('fk_order_item_product', 'order_item');
        $this->dropForeignKey('fk_order_item_product_variant', 'order_item');

        $this->dropTable('order_item');
    }
}
