<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=videogear_local',
            'username' => 'root',
            'password' => 'password1996',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'resourceManager' => [
            'class' => 'common\components\FileSystemResourceManager',
            'basePath' => Yii::getAlias('@backend/web/storage_aws_s3'),
            'directory' => 'storage_aws_s3'
        ]
    ],
];
