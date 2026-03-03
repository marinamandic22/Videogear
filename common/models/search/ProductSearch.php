<?php

namespace common\models\search;

use common\helpers\SearchHelper;
use common\models\ProductCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    public $q;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
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
    public function search($params): ActiveDataProvider
    {
        $query = Product::find()->joinWith('category');

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // Add category filtering with descendants
        if (!empty($this->category_id)) {
            $categoryIds = ProductCategory::getDescendantIds($this->category_id);
            $query->andWhere(['IN', 'product.category_id', $categoryIds]);
        }

        if (!empty($this->q)) {
            $this->q = str_replace("'", '', $this->q);
            $query = SearchHelper::addSearchQuery($query, $this->q, [
                'product.name',
                'product.sku',
                'product_category.name'
            ]);
        }

        return $dataProvider;
    }

    protected function getSort() {
        return [
            'defaultOrder' => ['order' => SORT_ASC],
            'attributes' => [
                'product' => [
                    'asc' => ['product.name' => SORT_ASC],
                    'desc' => ['product.name' => SORT_DESC],
                ],
                'category' => [
                    'asc' => ['product_category.name' => SORT_ASC],
                    'desc' => ['product_category.name' => SORT_DESC],
                ],
                'sku' => [
                    'asc' => ['product.sku' => SORT_ASC],
                    'desc' => ['product.sku' => SORT_DESC],
                ],
                'quantity' => [
                    'asc' => ['product.quantity' => SORT_ASC],
                    'desc' => ['product.quantity' => SORT_DESC],
                ],
                'price' => [
                    'asc' => ['product.price' => SORT_ASC],
                    'desc' => ['product.price' => SORT_DESC],
                ],
                'active' => [
                    'asc' => ['product.is_active' => SORT_ASC],
                    'desc' => ['product.is_active' => SORT_DESC],
                ],
                'order' => [
                    'asc' => ['product.order' => SORT_ASC],
                    'desc' => ['product.order' => SORT_DESC],
                ],
            ],
        ];
    }
}
