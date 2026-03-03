<?php

namespace common\models;

use common\components\orm\ActiveRecord;
use common\helpers\BaseHelper;
use common\helpers\CountryHelper;
use common\helpers\EmailHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $code
 * @property float|null $subtotal
 * @property float|null $total_tax
 * @property float|null $total_discount
 * @property float|null $shipping_cost
 * @property float|null $total
 * @property string|null $currency
 * @property int|null $status
 * @property string|null $delivery_first_name
 * @property string|null $delivery_last_name
 * @property string|null $delivery_address
 * @property string|null $delivery_city
 * @property string|null $delivery_zip
 * @property string|null $delivery_country
 * @property string|null $delivery_phone
 * @property string|null $delivery_notes
 * @property string|null $customer_ip_address
 * @property string|null $customer_user_agent
 * @property string|null $request
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property OrderItem[] $orderItems
 * @property User $user
 */
class Order extends ActiveRecord
{
    protected static $_i18nCategories = [
        'bs-BS' => 'app/feminine'
    ];

    const STATUS_PENDING = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_REFUNDED = 6;

    const SCENARIO_UPDATE_STATUS = 'update-status';
    const SCENARIO_ORDER_UPDATE = 'order-update';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['subtotal', 'total_tax', 'total_discount', 'shipping_cost', 'total'], 'number'],
            [['user_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted'], 'integer'],
            [['request'], 'string'],
            [['code', 'delivery_phone'], 'string', 'max' => 45],
            [['currency'], 'string', 'max' => 3],
            [['delivery_first_name', 'delivery_last_name', 'delivery_address', 'delivery_city', 'delivery_zip', 'delivery_country', 'delivery_notes', 'customer_ip_address', 'customer_user_agent'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_FAILED, self::STATUS_REFUNDED
            ]],
            [['user_id'], 'exist', 'targetRelation' => 'user'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'code' => Yii::t('app', 'Code'),
            'subtotal' => Yii::t('app', 'Subtotal'),
            'total_tax' => Yii::t('app', 'Total Tax'),
            'total_discount' => Yii::t('app', 'Total Discount'),
            'shipping_cost' => Yii::t('app', 'Shipping Cost'),
            'total' => Yii::t('app', 'Total'),
            'currency' => Yii::t('app', 'Currency'),
            'status' => Yii::t('app', 'Status'),
            'delivery_first_name' => Yii::t('app', 'Delivery First Name'),
            'delivery_last_name' => Yii::t('app', 'Delivery Last Name'),
            'delivery_address' => Yii::t('app', 'Delivery Address'),
            'delivery_city' => Yii::t('app', 'Delivery City'),
            'delivery_zip' => Yii::t('app', 'Delivery Zip'),
            'delivery_country' => Yii::t('app', 'Delivery Country'),
            'delivery_phone' => Yii::t('app', 'Delivery Phone'),
            'delivery_notes' => Yii::t('app', 'Delivery Notes'),
            'customer_ip_address' => Yii::t('app', 'Customer IP Address'),
            'customer_user_agent' => Yii::t('app', 'Customer User Agent'),
            'request' => Yii::t('app', 'Request'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    public function scenarios(): array
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_UPDATE_STATUS => ['status'],
            self::SCENARIO_ORDER_UPDATE => [
                'delivery_first_name', 'delivery_last_name', 'delivery_address', 'delivery_city', 'delivery_zip', 'delivery_country', 'delivery_phone', 'delivery_notes'
            ]
        ]);
    }

    public function fields(): array
    {
        $fields = parent::fields();

        $fields['order_items'] = function () {
            return $this->orderItems;
        };

        unset($fields['user_id']);
        unset($fields['customer_ip_address']);
        unset($fields['customer_user_agent']);
        unset($fields['request']);
        unset($fields['created_at']);
        unset($fields['created_by']);
        unset($fields['updated_at']);
        unset($fields['updated_by']);
        unset($fields['is_deleted']);

        return $fields;
    }

    public function getFormattedDeliveryAddress(): string
    {
        $cityInfoArray = [$this->delivery_zip, $this->delivery_city];
        $cityInfo = BaseHelper::formatToCharSeparatedString($cityInfoArray, ' ');

        $items = [$this->delivery_address, $cityInfo,];

        if ($this->delivery_country) {
            $items[] = CountryHelper::getNameByCode($this->delivery_country);
        }

        return BaseHelper::formatToCharSeparatedString($items, ',<br>');
    }

    public function getCustomerFullName(): string
    {
        $nameArray = [$this->delivery_first_name, $this->delivery_last_name];
        return BaseHelper::formatToCharSeparatedString($nameArray, ' ');
    }

    public function getTotalOrderItems(): bool|int|string|null
    {
        return $this->getOrderItems()->count();
    }

    public function sendNewOrderEmail(): bool
    {
        return EmailHelper::sendMessage(
            ['html' => 'new-order'],
            Yii::$app->params['admin.email'],
            Yii::t("app", "You have a new order {code}", [
                'code' => "#{$this->code}"
            ]),
            ['model' => $this]
        );
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return ActiveQuery
     */
    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->onCondition([]);
    }
}
