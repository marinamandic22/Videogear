<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use yii\data\ActiveDataProvider;
use common\models\ProductCategory;

/**
 * ProductCategorySearch represents the model behind the search form of `common\models\ProductCategory`.
 */
class ProductCategorySearch extends ProductCategory
{
    public $q;
    public $onlyTopLevel;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_category_id'], 'integer'],
            [['q'], 'string'],
            [['q'], 'filter', 'filter' => 'trim'],
            [['onlyTopLevel'], 'boolean'], // Add validation rule
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
        $query = ProductCategory::find();

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

        // Filter for only parent categories if requested
        if ($this->onlyTopLevel) {
            $query->andWhere(['parent_category_id' => null]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_category_id' => $this->parent_category_id,
            'is_active' => $this->is_active
        ]);

        if (!empty($this->q)) {
            $query->leftJoin('product_category as pc', 'pc.parent_category_id = product_category.id');
            $this->q = str_replace("'", '', $this->q);
            $query = SearchHelper::addSearchQuery($query, $this->q, [
                'product_category.name',
                'pc.name'
            ]);
        }

        return $dataProvider;
    }

    protected function getSort()
    {
        return [
            'defaultOrder' => ['order' => SORT_ASC],
            'attributes' => [
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                ],
                'active' => [
                    'asc' => ['is_active' => SORT_ASC],
                    'desc' => ['is_active' => SORT_DESC],
                ],
                'order' => [
                    'asc' => ['order' => SORT_ASC],
                    'desc' => ['order' => SORT_DESC],
                ],
            ],
        ];
    }
}
