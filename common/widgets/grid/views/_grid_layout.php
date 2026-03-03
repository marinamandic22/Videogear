<?php

use yii\web\View;

/**
 * @var View $this
 * Available elements: {summary} {pager} {items} {collapsable}
 */
?>

<div class="grid-layout">
    <div class="d-flex flex-wrap mb-3 flex-column-reverse flex-md-row">
        <div class="col-search">{search}</div>
        <div class="ml-md-auto my-auto">
            <div class="d-flex align-items-center justify-content-md-end flex-wrap flex-md-nowrap">
                {collapsable}{add}
            </div>
        </div>
    </div>
    <div class="table-responsive">
        {items}
    </div>
    <div class="d-flex justify-content-between">
        {summary}{pager}
    </div>
</div>