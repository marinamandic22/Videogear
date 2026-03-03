<?php

namespace api\models;

use common\helpers\PriceHelper;
use common\helpers\TimeHelper;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductVariant;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderForm extends Order
{
    private array $_order_items = [];

    public string $name;
    public string $email;
    public string $address;
    public string $city;
    public string $zip;
    public string $phone;
    public string $notes;
    public array $order_items;

    public function rules(): array
    {
        return [
            [['name', 'address', 'city', 'zip', 'phone', 'notes', 'order_items'], 'safe'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['address', 'city', 'zip', 'phone', 'notes', 'email'], 'filter', 'filter' => 'trim'],
        ];
    }

    public function save($runValidation = true, $attributeNames = null): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        if (!$this->validateOrderItems()) {
            return false;
        }

        if (!$this->prepareOrderItems()) {
            return false;
        }

        if (!$this->checkProductStock()) {
            return false;
        }

        if (!$this->prepareOrder()) {
            return false;
        }

        if (!$this->user_id) {
            // First try to find existing user by email or username
            $existingUser = User::find()
                ->where(['email' => $this->email])
                ->orWhere(['username' => $this->email])
                ->one();

            if ($existingUser) {
                $this->user_id = $existingUser->id;
            } else {
                // Only create guest user if no existing user found
                if (!$this->createGuestUser()) {
                    $transaction->rollBack();
                    return false;
                }
            }
        }

        if (!parent::save($runValidation, $attributeNames)) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->saveOrderItems()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->updateProductStock()) {
            $transaction->rollBack();
            return false;
        }

        if (!$this->sendNewOrderEmail()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    private function validateOrderItems(): bool
    {
        foreach ($this->order_items as $item) {
            if (!$this->validateOrderItem($item)) {
                return false;
            }
        }
        return true;
    }

    private function validateOrderItem($item): bool
    {
        if ((!isset($item['product_id']) && !isset($item['product_variant_id'])) || !isset($item['quantity'])) {
            $this->addError('order_item', 'Order item invalid body !');
            return false;
        }

        if ($item['quantity'] < 1) {
            $this->addError('order_items', 'Order item quantity must be greater then 0 !');
            return false;
        }

        return true;
    }

    private function prepareOrderItems(): bool
    {
        foreach ($this->order_items as $item) {
            if (!$this->prepareOrderItem($item)) {
                return false;
            }
        }
        return true;
    }

    private function prepareOrderItem($item): bool
    {
        $price = 0;
        $quantity = $item['quantity'];
        $productId = null;

        if (!empty($item['product_variant_id'])) {
            $model = ProductVariant::findOne($item['product_variant_id']);

            if (empty($model)) {
                $this->addError('order_items', 'Product variant is missing !');
                return false;
            }

            $productId = $model->product_id;
            $price = $model->price ?: $model->product->price;
        } elseif (!empty($item['product_id'])) {
            $model = Product::findOne($item['product_id']);

            if (empty($model)) {
                $this->addError('order_items', 'Product is missing !');
                return false;
            }
            $productId = $model->id;
            $price = $model->price;
        }

        $this->_order_items[] = [
            'product_id' => $productId,
            'product_variant_id' => $item['product_variant_id'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $price,
            'total' => $item['quantity'] * $price,
        ];

        return true;
    }

    private function checkProductStock(): bool
    {
        foreach ($this->_order_items as $item) {
            if (!empty($item['product_variant_id'])) {
                $model = ProductVariant::findOne($item['product_variant_id']);

                if (empty($model)) {
                    $this->addError('order_items', 'Product variant not found !');
                    return false;
                }

                if (isset($model->stock) && $model->stock < $item['quantity']) {
                    $this->addError('order_items', Yii::t('app', 'Insufficient stock for product variant !'));
                    return false;
                }
            } else {
                $model = Product::findOne($item['product_id']);

                if (empty($model)) {
                    $this->addError('order_items', 'Product not found !');
                    return false;
                }

                if (isset($model->stock) && $model->stock < $item['quantity']) {
                    $this->addError('order_items', Yii::t('app', 'Insufficient stock for product !'));
                    return false;
                }
            }
        }

        return true;
    }

    private function updateProductStock(): bool
    {
        foreach ($this->_order_items as $item) {
            if (!empty($item['product_variant_id'])) {
                $model = ProductVariant::findOne($item['product_variant_id']);

                if (empty($model)) {
                    $this->addError('order_items', 'Product variant not found !');
                    return false;
                }

                if (isset($model->quantity)) {
                    $model->quantity -= $item['quantity'];

                    if (!$model->save(false)) {
                        $this->addError('order_items', 'Failed to update product variant quantity !');
                        return false;
                    }
                }
            } else {
                $model = Product::findOne($item['product_id']);

                if (empty($model)) {
                    $this->addError('order_items', 'Product not found !');
                    return false;
                }

                if (isset($model->quantity)) {
                    $model->quantity -= $item['quantity'];
                    if (!$model->save(false)) {
                        $this->addError('order_items', 'Failed to update product quantity !');
                        return false;
                    }
                }
            }
        }

        return true;
    }

    private function prepareOrder(): bool
    {
        $request = Yii::$app->getRequest();

        $total = $this->calculateTotal();
        $tax = PriceHelper::extractTax($total);
        $subtotal = $total - $tax;

        $_user = Yii::$app->user->identity;

        $nameArray = explode(' ', preg_replace('/\s+/', ' ', trim($this->name)));
        $deliveryFirstName = array_shift($nameArray);
        $deliveryLastName = implode(' ', $nameArray);

        $this->setAttributes([
            'user_id' => ArrayHelper::getValue($_user, 'id'),
            'code' => Yii::$app->params['orderNoPrefix'] . $this->getNextId(),
            'delivery_first_name' => $deliveryFirstName,
            'delivery_last_name' => $deliveryLastName,
            'delivery_phone' => $this->phone,
            'delivery_address' => $this->address,
            'delivery_city' => $this->city,
            'delivery_zip' => $this->zip,
            'delivery_country' => Yii::$app->params['deliveryCountry'],
            'delivery_notes' => $this->notes,
            'request' => Json::encode($request->getBodyParams()),
            'customer_ip_address' => $request->getUserIP(),
            'customer_user_agent' => $request->getUserAgent(),
            'currency' => Yii::$app->params['currency'],
            'status' => self::STATUS_PENDING,
            'total' => $total,
            'total_tax' => $tax,
            'subtotal' => $subtotal,
        ], false);

        return true;
    }

    private function calculateTotal(): float|int
    {
        return array_sum(
            array_map(function ($item) {
                return $item['total'];
            }, $this->_order_items)
        );
    }

    private function createGuestUser(): bool
    {
        $model = new User([
            'username' => $this->email,
            'email' => $this->email,
            'status' => User::STATUS_INACTIVE,
            'first_name' => $this->delivery_first_name ?: ' ',
            'last_name' => $this->delivery_last_name ?: ' ',
            'address' => $this->address,
            'country' => Yii::$app->params['deliveryCountry'],
            'city' => $this->city,
            'zip' => $this->zip,
            'phone' => $this->phone
        ]);

        if (!$model->save()) {
            $this->addError('user', $model->getFirstErrors());
            return false;
        }

        $this->user_id = $model->id;

        return true;
    }

    private function saveOrderItems(): bool
    {
        foreach ($this->_order_items as $item) {
            $model = new OrderItem([
                'order_id' => $this->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

            if (!$model->save()) {
                $this->addError('order_item', $model->getFirstErrors());
                return false;
            }
        }

        return true;
    }
}
