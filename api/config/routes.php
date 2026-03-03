<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/swagger',
        'pluralize' => false,
        'patterns' => [
            'GET' => 'docs',
            'GET index' => 'docs',
            'GET json-schema' => 'json-schema',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/oauth',
        'pluralize' => false,
        'patterns' => [
            'POST token' => 'token',
            'POST revoke' => 'revoke',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/image',
        'patterns' => [
            'GET <id>/thumb/<spec>' => 'thumb',
            'OPTIONS <id>/thumb/<spec>/<slug>' => 'options',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'pluralize' => false,
        'patterns' => [
            'GET info' => 'info',
            'POST register' => 'register',
            'PUT update-info' => 'update',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/category',
        'patterns' => [
            'GET' => 'index',
            'GET index' => 'index',
            'GET <id>' => 'view',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/product',
        'patterns' => [
            'GET' => 'index',
            'GET index' => 'index',
            'GET <id>' => 'view',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/order',
        'patterns' => [
            'POST' => 'create',
            'OPTIONS <action>' => 'options',
            '' => 'options',
        ],
    ]
];
