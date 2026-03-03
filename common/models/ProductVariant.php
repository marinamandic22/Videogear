<?php

namespace common\models;

use common\components\orm\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "product_variant".
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property string|null $sku
 * @property int|null $quantity
 * @property float|null $price
 * @property int|null $order
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Product $product
 */
class ProductVariant extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_variant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'required'],
            [['product_id', 'quantity', 'order', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted'], 'integer'],
            [['price'], 'number'],
            [['name', 'sku'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'name' => Yii::t('app', 'Name'),
            'sku' => Yii::t('app', 'SKU'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'sku',
            'price',
            'quantity'
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
