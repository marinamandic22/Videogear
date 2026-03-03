<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class CustomerSearch extends User
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
        $query = User::find()->where(['is_staff' => 0]);

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
            'id' => $this->id
        ]);

        if (!empty($this->q)) {
            $this->q = str_replace("'", '', $this->q);
            $query = SearchHelper::addSearchQuery($query, $this->q, [
                'CONCAT(first_name, " ", last_name)',
                'CONCAT(city, ", ", country, ", ", zip)',
                'email',
                'phone',
                'address',
                'city',
                'country',
                'zip'
            ]);
        }

        return $dataProvider;
    }

    protected function getSort() {
        return [
            'attributes' => [
                'user' => [
                    'asc' => ["CONCAT(first_name, ' ', last_name)" => SORT_ASC],
                    'desc' => ["CONCAT(first_name, ' ', last_name)" => SORT_DESC],
                ],
                'contact' => [
                    'asc' => ['email' => SORT_ASC, 'phone' => SORT_ASC,],
                    'desc' => ['email' => SORT_DESC, 'phone' => SORT_DESC],
                ],
                'location' => [
                    'asc' => ['address' => SORT_ASC, 'city' => SORT_ASC, 'country' => SORT_ASC, 'zip' => SORT_ASC],
                    'desc' => ['address' => SORT_DESC, 'city' => SORT_DESC, 'country' => SORT_DESC, 'zip' => SORT_DESC],
                ],
            ],
        ];
    }
}
