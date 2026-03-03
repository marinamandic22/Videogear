<?php

use yii\db\Migration;

/**
 * Class m220512_194700_alter_table_product_category_slug_is_active
 */
class m220512_194700_alter_table_product_category_slug_is_active extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product_category', 'slug', $this->string(255)->after('name'));
        $this->addColumn('product_category', 'is_active', $this->tinyInteger()->after('order')->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product_category', 'slug');
        $this->dropColumn('product_category', 'is_active');
    }
}
