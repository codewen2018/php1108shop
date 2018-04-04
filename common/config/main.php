<?php
return [
    'timeZone'=>'PRC',//时区
    'language'=>'zh-CN',//语言
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                 'class' => 'Swift_SmtpTransport',
                 'host' => 'smtp.163.com',//邮箱地址
                 'username' => 'liu3chao',//用户名名
                 'password' => 'php1108',//客户端授权码
                 'port' => '25',//端口
                 //'encryption' => 'tls',
             ],
           // 'useFileTransport' => true,
        ],
    ],
];
