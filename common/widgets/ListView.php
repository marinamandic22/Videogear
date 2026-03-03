<?php

namespace common\widgets;

class ListView extends \yii\widgets\ListView
{
    public $pager = [
        'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
        'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
        'prevPageLabel' => '<i class="fas fa-angle-left"></i>',
        'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
        'maxButtonCount' => 3,
        'pageCssClass' => 'page-item',
        'firstPageCssClass' => 'page-item',
        'lastPageCssClass' => 'page-item',
        'nextPageCssClass' => 'page-item',
        'prevPageCssClass' => 'page-item',
        'options' => [
            'class' => 'pagination justify-content-end',
        ],
        'linkOptions' => ['class' => 'page-link'],
    ];

    public $layout = "<div class='mb-3 overflow-auto'>{items}</div><div class='d-flex align-items-center justify-content-between'>{summary}{pager}</div>";
}
