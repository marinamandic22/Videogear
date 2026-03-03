<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'YHMosqHMaIBsqJkZsM0o2v1vofaziPOL',
        ],
    ],
    'bootstrap' => ['debug', 'gii'],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module'
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*']
        ]
    ]
];


return $config;
