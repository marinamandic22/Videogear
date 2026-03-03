<?php

namespace common\helpers;

use common\models\ProductCategory;
use yii\helpers\ArrayHelper;

class ProductCategoryHelper
{
    public static function getParentCategorySelectList(ProductCategory $model)
    {
        $query = ProductCategory::find();

        if (!$model->isNewRecord) {
            $query->where(['!=', 'id', $model->id])->andWhere([
                'OR',
                ['!=', 'parent_category_id', $model->id],
                ['parent_category_id' => null]
            ]);
        }

        return ArrayHelper::map($query->all(), 'id', 'name');
    }
}