<?php

use yii\db\Migration;

/**
 * Class m211121_143648_add_column_sku_to_product_table
 */
class m211121_143648_add_column_sku_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'sku', $this->string(255)->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'sku');
    }
}
