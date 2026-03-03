<?php

use yii\db\Migration;

/**
 * Class m211121_101428_add_column_quantity_to_product_table
 */
class m211121_101428_add_column_quantity_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'quantity', $this->integer()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'quantity');
    }
}
