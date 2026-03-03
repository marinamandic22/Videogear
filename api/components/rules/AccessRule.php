<?php

namespace api\components\rules;

class AccessRule extends \yii\filters\AccessRule
{
    public $scopes = [];
}
