<?php

namespace common\models;

use common\components\orm\ActiveRecord;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $cover_image_id
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $sku
 * @property int|null $quantity
 * @property float|null $price
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $order
 * @property int|null $is_active
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property ProductCategory $category
 * @property Image $coverImage
 * @property ProductImage[] $productImages
 * @property ProductVariant[] $productVariants
 */
class Product extends ActiveRecord
{
    protected static $_i18nCategories = [
        'bs-BS' => 'app/masculine'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['name', 'category_id', 'price'], 'required'],
            [['price'], 'number', 'min' => 0, 'max' => 99999999.99],
            [['quantity'], 'integer', 'min' => 0],
            [['quantity'], 'default', 'value' => 0],
            [['name', 'short_description', 'sku', 'slug'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['category_id', 'cover_image_id', 'order', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted'], 'integer'],
            [['cover_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['cover_image_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['is_active'], 'default', 'value' => static::STATUS_ACTIVE],
            ['is_active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['sku'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category'),
            'cover_image_id' => Yii::t('app', 'Cover Image ID'),
            'name' => Yii::t('app', 'Name'),
            'slug' => Yii::t('app', 'Slug'),
            'sku' => Yii::t('app', 'SKU'),
            'quantity' => Yii::t('app', 'Quantity'),
            'price' => Yii::t('app', 'Price'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'order' => Yii::t('app', 'Order'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted')
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
        ]);
    }

    public function fields()
    {
        return [
            'id',
            'category_id',
            'cover_image_id',
            'name',
            'slug',
            'sku',
            'quantity',
            'price',
            'short_description',
            'description',
            'variants' => function() {
                return $this->productVariants;
            },
            'additional_image_ids' => function() {
                return array_map(function (ProductImage $productImage) {
                    return $productImage->image_id;
                }, $this->productImages);
            }
        ];
    }

    public static function findOneBySlug($slug)
    {
        return static::findOne(['slug' => $slug]);
    }

    public function getAllProductImages(): array
    {
        $images = [];

        if(!empty($this->coverImage)) {
            $images[] = $this->coverImage;
        }

        foreach ($this->getOrderedProductImages() as $productImage) {
            /* @var $productImage ProductImage */
            $images[] = $productImage->image;
        }

        return $images;
    }

    /**
     * @return array
     */
    protected function getOrderedProductImages(): array
    {
        return $this->getProductImages()->orderBy(['order' => SORT_ASC])->all();
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[CoverImage]].
     *
     * @return ActiveQuery
     */
    public function getCoverImage(): ActiveQuery
    {
        return $this->hasOne(Image::className(), ['id' => 'cover_image_id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return ActiveQuery
     */
    public function getProductImages(): ActiveQuery
    {
        return $this->hasMany(ProductImage::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductVariants]].
     *
     * @return ActiveQuery
     */
    public function getProductVariants(): ActiveQuery
    {
        return $this->hasMany(ProductVariant::className(), ['product_id' => 'id']);
    }
}
