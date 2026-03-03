<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $q;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['q'], 'string'],
            [['q'], 'filter', 'filter' => 'trim']
        ];
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
        // Create a fresh ActiveQuery without any behaviors or scopes
        $query = new ActiveQuery(User::class);
        $query->from(['u' => User::tableName()])
            ->leftJoin('auth_assignment aa', 'aa.user_id = u.id')
            ->where([
                'u.is_staff' => 1,
                'COALESCE(u.is_deleted, 0)' => 0
            ])
            ->groupBy('u.id');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'u.id' => $this->id,
            'u.status' => $this->status
        ]);

        if (!empty($this->q)) {
            $this->q = str_replace("'", '', $this->q);
            $query = SearchHelper::addSearchQuery($query, $this->q, [
                'CONCAT(u.first_name, " ", u.last_name)',
                'CONCAT(u.city, ", ", u.country, ", ", u.zip)',
                'u.email',
                'u.phone',
                'u.address',
                'u.city',
                'u.country',
                'u.zip'
            ]);
        }

        return $dataProvider;
    }

    protected function getSort() {
        return [
            'attributes' => [
                'user' => [
                    'asc' => ["CONCAT(u.first_name, ' ', u.last_name)" => SORT_ASC],
                    'desc' => ["CONCAT(u.first_name, ' ', u.last_name)" => SORT_DESC],
                ],
                'role' => [
                    'asc' => ['COALESCE(aa.item_name, "customer")' => SORT_ASC],
                    'desc' => ['COALESCE(aa.item_name, "customer")' => SORT_DESC],
                ],
                'contact' => [
                    'asc' => ['u.email' => SORT_ASC, 'u.phone' => SORT_ASC],
                    'desc' => ['u.email' => SORT_DESC, 'u.phone' => SORT_DESC],
                ],
                'location' => [
                    'asc' => ['u.address' => SORT_ASC, 'u.city' => SORT_ASC, 'u.country' => SORT_ASC, 'u.zip' => SORT_ASC],
                    'desc' => ['u.address' => SORT_DESC, 'u.city' => SORT_DESC, 'u.country' => SORT_DESC, 'u.zip' => SORT_DESC],
                ],
                'status' => [
                    'asc' => ['u.status' => SORT_ASC],
                    'desc' => ['u.status' => SORT_DESC],
                ]
            ],
        ];
    }
}
