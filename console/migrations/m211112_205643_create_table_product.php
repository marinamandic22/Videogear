<?php

use yii\db\Migration;

/**
 * Class m211112_205643_create_table_product
 */
class m211112_205643_create_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'cover_image_id' => $this->integer(),
            'name' => $this->string(255),
            'slug' => $this->string(255),
            'price' => $this->decimal(10, 2),
            'short_description' => $this->string(255),
            'description' => $this->text(),
            'order' => $this->integer(),
            'is_active' => $this->tinyInteger(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->tinyInteger()->defaultValue(0)
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('fk_product_product_category', 'product', 'category_id', 'product_category', 'id');
        $this->addForeignKey('fk_product_image', 'product', 'cover_image_id', 'image', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_product_product_category', 'product');
        $this->dropForeignKey('fk_product_image', 'product');

        $this->dropTable('product');
    }
}
