<?php

use yii\db\Migration;

/**
 * Class m211121_153847_create_table_product_variant
 */
class m211121_153847_create_table_product_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_variant', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string(255),
            'sku' => $this->string(255),
            'quantity' => $this->integer(),
            'price' => $this->decimal(10, 2),
            'order' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('fk_product_variant_product', 'product_variant', 'product_id', 'product', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_variant_product', 'product_variant');
        $this->dropTable('product_variant');
    }
}
