<?php

use yii\db\Migration;

/**
 * Class m211112_210314_create_table_product_image
 */
class m211112_210314_create_table_product_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_image', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'image_id' => $this->integer(),
            'order' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ]);

        $this->addForeignKey('fk_product_image_product', 'product_image', 'product_id', 'product', 'id');
        $this->addForeignKey('fk_product_image_image', 'product_image', 'image_id', 'image', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_image_product', 'product_image');
        $this->dropForeignKey('fk_product_image_image', 'product_image');

        $this->dropTable('product_image');
    }
}
