<?php

use yii\db\Migration;

/**
 * Class m211031_160153_create_table_product_category
 */
class m211031_160153_create_table_product_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product_category', [
            'id' => $this->primaryKey(),
            'parent_category_id' => $this->integer(),
            'cover_image_id' => $this->integer(),
            'name' => $this->string(255),
            'description' => $this->text(),
            'order' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('fk_parent_product_category', 'product_category', 'parent_category_id', 'product_category', 'id');
        $this->addForeignKey('fk_product_category_image', 'product_category', 'cover_image_id', 'image', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_parent_product_category', 'product_category');
        $this->dropForeignKey('fk_product_category_image', 'product_category');

        $this->dropTable('product_category');
    }
}
