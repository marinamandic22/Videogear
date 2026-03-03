<?php

namespace api\extensions;

use yii\filters\Cors;

class CorsFilter extends Cors
{
    public $allowedHeaders = [
        'X-Pagination-Current-Page',
        'X-Pagination-Page-Count',
        'X-Pagination-Per-Page',
        'X-Pagination-Total-Count',
        'Access-Control-Allow-Origin',
        'Access-Control-Allow-Headers',
        'Access-Control-Allow-Methods'
    ];

    public function init()
    {
        $this->cors['Access-Control-Expose-Headers'] =
            array_merge($this->cors['Access-Control-Expose-Headers'], $this->allowedHeaders);
    }
}