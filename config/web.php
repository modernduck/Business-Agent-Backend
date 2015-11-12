<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'abcasd',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            /*'enableStrictParsing' => true,*/
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'brand',
                    'extraPatterns' => [
                            'GET token-by-email' => 'token-by-email',
                            'GET {id}/token' => 'token',
                            'GET {id}/check' => 'check',
                            'GET test' => 'test',
                            'GET {id}/config' => 'config',
                            'GET {id}/import' => 'import',
                    ],
                    
                ],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'branch',
                    'extraPatterns' => [
                            
                            'GET {id}/inventories' => 'inventory-index',
                            'POST {id}/inventories' => 'inventory-create',
                            'GET <id:\d+>/inventories/<product_id:\d+>' => 'inventory-get',
                            'PUT <id:\d+>/inventories/<product_id:\d+>' => 'inventory-update',
                            'OPTIONS <id:\d+>/inventories' => 'inventory-index',
                            'OPTIONS <id:\d+>/inventories/<product_id:\d+>' => 'inventory-update',
                            'DELETE <id:\d+>/inventories/<product_id:\d+>' => 'inventory-delete',
                            'GET <branch_id:\d+>/employees' => 'employee-index',
                            'OPTIONS <branch_id:\d+>/employees' => 'employee-index',
                            'POST <branch_id:\d+>/employees' => 'employee-create',
                            'GET <branch_id:\d+>/employees/<employee_id:\d+>' => 'employee-get',
                            'PUT <branch_id:\d+>/employees/<employee_id:\d+>' => 'employee-update',
                            'DELETE <branch_id:\d+>/employees/<employee_id:\d+>' => 'employee-delete',

                            
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'product-type'],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'product',
                    'extraPatterns' => [
                        'POST <id:\d+>/upload' => 'upload',
                        'OPTIONS <id:\d+>/upload' => 'upload',
                        'GET  <id:\d+>/test' => 'test',
                    ]
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'sale'],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => 'pos',
                    'extraPatterns' => [
                        'GET branches/<brand_id:\d+>' => 'branch',
                        'GET authen/<branch_id:\d+>' => 'authen',
                        'GET token' => 'token',
                        'GET inventories' => 'inventories',
                    ]
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\Brand', // User must implement the IdentityInterface
            'enableSession' => false,
            // 'loginUrl' => ['user/login'],
            // ...
        ]
    ],
    'params' => $params,
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
