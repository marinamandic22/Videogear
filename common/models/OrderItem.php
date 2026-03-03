<?php

namespace common\models;

use common\components\orm\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int|null $product_variant_id
 * @property int|null $quantity
 * @property float|null $price
 * @property float|null $total
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Order $order
 * @property Product $product
 * @property ProductVariant $productVariant
 *
 * @property Product $productWithDeleted
 * @property ProductVariant $productVariantWithDeleted
 *
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id'], 'required'],
            [['order_id', 'product_id', 'product_variant_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted'], 'integer'],
            [['price', 'total'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductVariant::className(), 'targetAttribute' => ['product_variant_id' => 'id']],
            [['quantity'], 'integer', 'min' => 1, 'max' => 100000]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_variant_id' => Yii::t('app', 'Product Variant ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'total' => Yii::t('app', 'Total'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['created_at']);
        unset($fields['created_by']);
        unset($fields['updated_at']);
        unset($fields['updated_by']);
        unset($fields['is_deleted']);

        return $fields;
    }

    public function getProductWithDeleted()
    {
        if (empty($this->product_id)) {
            return null;
        }

        return Product::findWithDeleted()->where([
            'id' => $this->product_id
        ])->one();
    }

    public function getProductVariantWithDeleted()
    {
        if (empty($this->product_variant_id)) {
            return null;
        }

        return ProductVariant::findWithDeleted()->where([
            'id' => $this->product_variant_id
        ])->one();
    }

    /**
     * Gets query for [[Order]].
     *
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
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

    /**
     * Gets query for [[ProductVariant]].
     *
     * @return ActiveQuery
     */
    public function getProductVariant()
    {
        return $this->hasOne(ProductVariant::className(), ['id' => 'product_variant_id']);
    }
}
