<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use common\helpers\TimeHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;
use yii\db\ActiveQuery;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    public $showTodayOrders = false;
    public $q;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['q'], 'string'],
            [['q'], 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find()->joinWith(['user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $this->getSort()
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->showTodayOrders) {
            $todayDate = new \DateTime('today');

            $query->andFilterWhere([
                '>=', 'order.created_at', $todayDate->getTimestamp()
            ]);
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id
        ]);

        if (!empty($this->q)) {
            $this->q = str_replace("'", '', $this->q);
            $query = SearchHelper::addSearchQuery($query, $this->q, [
                'CONCAT(user.first_name, " ", user.last_name)',
                'CONCAT(delivery_city, ", ", delivery_country, ", ", delivery_zip)',
                'delivery_address',
                'delivery_city',
                'delivery_country',
                'delivery_zip',
                'code'
            ]);
        }

        return $dataProvider;
    }

    protected function getSort() {
        return [
            'defaultOrder' => [
                "order" => SORT_DESC
            ],
            'attributes' => [
                'order' => [
                    'asc' => ["order.created_at" => SORT_ASC],
                    'desc' => ["order.created_at" => SORT_DESC],
                ],
                'customer' => [
                    'asc' => ["CONCAT(user.first_name, ' ', user.last_name)" => SORT_ASC],
                    'desc' => ["CONCAT(user.first_name, ' ', user.last_name)" => SORT_DESC],
                ],
                'delivery_address' => [
                    'asc' => [
                        'delivery_address' => SORT_ASC,
                        'delivery_city' => SORT_ASC,
                        'delivery_country' => SORT_ASC,
                        'delivery_zip' => SORT_ASC
                    ],
                    'desc' => [
                        'delivery_address' => SORT_DESC,
                        'delivery_city' => SORT_DESC,
                        'delivery_country' => SORT_DESC,
                        'delivery_zip' => SORT_DESC
                    ],
                ],
                'subtotal',
                'total_tax',
                'total',
            ],
        ];
    }

    public function getPendingOrdersCount(ActiveQuery $query) {
        $q = clone($query);
        return $q->andWhere(['order.status' => self::STATUS_PENDING])->count();
    }

    public function getProcessingOrdersCount(ActiveQuery $query) {
        $q = clone($query);
        return $q->andWhere(['order.status' => self::STATUS_PROCESSING])->count();
    }

    public function getCompletedOrdersCount(ActiveQuery $query) {
        $q = clone($query);
        return $q->andWhere(['order.status' => self::STATUS_COMPLETED])->count();
    }

    public function getOrdersTotalSum(ActiveQuery $query) {
        $q = clone($query);

        return $q->sum('total');
    }
}
