<?php

namespace common\helpers;

use yii\db\ActiveQuery;

class SearchHelper
{
    public static function addSearchQuery(ActiveQuery $query, $q, array $columns) {
        if(empty($columns)) {
            return $query;
        }

        $conditions = ['OR'];
        foreach ($columns as $column) {
            $conditions[] = ['LIKE', $column, $q];
        }

        return $query->andFilterWhere($conditions);
    }
}