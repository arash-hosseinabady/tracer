<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'fa-IR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'homeUrl' => array('/site'),
    'defaultRoute' => '/site',
    //'catchAll' => ['site/offline'],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'POST <controller:[\w-]+>s' => '<controller>/create',
                '<controller:[\w-]+>s' => '<controller>/index',
                'PUT <controller:[\w-]+>/<id:\d+>' => '<controller>/update',
                'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',
                '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
            ],
        ],
        'formatter' => [
            'booleanFormat' => array(Yii::t('app', 'No'), Yii::t('app', 'Yes')),
            'class' => 'yii\i18n\Formatter',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
            'locale' => 'eng',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6o0TakbMHgcHC7nl55ROD7XMFUs9BLOO',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'jdate' => [
            'class' => 'mjm\jdate\DateTime'
        ],
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            // Comment this if you don't want to record user logins
            'on afterLogin' => function ($event) {
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            },
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
    'modules' => [
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            'on beforeAction' => function (yii\base\ActionEvent $event) {
                if ($event->action->uniqueId == 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                }
            },
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;