<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'pt-BR',
    'bootstrap' => ['log', 'admin', 'elaborar', 'executar', 'monitorar', 'avaliar', 'queue'],
    'timeZone' => 'America/Sao_Paulo',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@admin' => '/admin',
        '@elaborar' => '/elaborar',
        '@executar' => '/executar',
        '@monitorar' => '/monitorar',
        '@avaliar' => '/avaliar',
        '@auditor' => '/auditor',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'elaborar' => [
            'class' => 'app\modules\elaborar\Module',
        ],
        'executar' => [
            'class' => 'app\modules\executar\Module',
        ],
        'monitorar' => [
            'class' => 'app\modules\monitorar\Module',
        ],
        'avaliar' => [
            'class' => 'app\modules\avaliar\Module',
        ],
        'auditor' => [
            'class' => 'app\modules\auditor\Module',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
        'rbac' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'menus' => [
            ],
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'app\models\User',
                    'idField' => 'id',
                    'usernameField' => 'nome',
                    'searchClass' => 'app\models\pesquisa\UserSearch'
                ]
            ],
            'as access' => [
                'class' => 'mdm\admin\components\AccessControl',
                'allowActions' => [
                    'site/login',
                ]
            ]
        ],
    ],
    'components' => [
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
        'assetManager' => [
            'bundles' => [
                'wbraganca\dynamicform\DynamicFormAsset' => [
                    'sourcePath' => '@app/web/js',
                    'js' => [
                        'yii2-dynamic-form.js',7
                    ],
                ],
            ],
        ],
        'session' => [
            'name' => '_pmpi_session_itabirito',
            'cookieParams' => [
                'sameSite' => PHP_VERSION_ID >= 70300 ? \yii\web\Cookie::SAME_SITE_LAX : null
            ]
        ],
        'authManager' => [
            'class' => 'app\components\rbac\DbManagerCustom',
        ],
        'request' => [
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY'),
            'csrfParam' => '_csrf-pmpi',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'pmpi',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_pmpi_identity_itabirito',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),         // Lê do seu .env [cite: 7]
                'username' => getenv('SMTP_USER'),     // Lê do seu .env [cite: 7]
                'password' => getenv('SMTP_PASS'),     // Lê do seu .env [cite: 7]
                'port' => getenv('SMTP_PORT'),         // Lê do seu .env [cite: 7]
                'encryption' => getenv('SMTP_ENCRYPTION') ?: null,
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                    ],
                ],
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => [getenv('EMAIL_ENVIO') => getenv('NOME_REMETENTE')]
            ]
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
        'db' => $db,
        'urlManager' => [
            'baseUrl' => getenv('BASE_URL'),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller>/<action:(view|update)>/<id:\d+>' => '<controller>/<action>',
                'arquivo/download/<token:[^/]+>' => 'arquivo/download',
                [
                    'pattern' => '<controller>/<action:(index)>/<page:\d+>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'defaults' => ['id' => '']
                ]
            ],
        ],
    ],
    'params' => $params,
];

// if (YII_ENV_DEV) {
//     // configuration adjustments for 'dev' environment
//     $config['bootstrap'][] = 'debug';
//     $config['modules']['debug'] = [
//         'class' => 'yii\debug\Module',
//         // uncomment the following to add your IP if you are not connecting from localhost.
//         //'allowedIPs' => ['127.0.0.1', '::1'],
//     ];

//     $config['bootstrap'][] = 'gii';
//     $config['modules']['gii'] = [
//         'class' => 'yii\gii\Module',
//         // uncomment the following to add your IP if you are not connecting from localhost.
//         //'allowedIPs' => ['127.0.0.1', '::1'],
//     ];
// }

// Configuração Global do Widget TinyMCE usando o namespace específico do projeto
\Yii::$container->set('app\components\widgets\TinyMCE', [
    'apiKey' => getenv('TINYMCE_API_KEY'),
]);

return $config;
