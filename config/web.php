<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'pt-BR',
    'bootstrap' => ['log', 'admin', 'elaborar', 'executar', 'monitorar', 'avaliar'],
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
                        'yii2-dynamic-form.js',
                    ],
                ],
            ],
        ],
        'session' => [
            'name' => 'NAME_SESSION',
            'cookieParams' => [
                'sameSite' => PHP_VERSION_ID >= 70300 ? \yii\web\Cookie::SAME_SITE_LAX : null
            ]
        ],
        'authManager' => [
            'class' => 'app\components\rbac\DbManagerCustom',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'COOKIE_VALIDATION_KEY',
            'csrfParam' => 'NAME_CSRF',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'pmpi',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => 'NAME_IDENTITY_COOKIE',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $_SERVER['SMTP_HOST'] ?? getenv('SMTP_HOST'),
                'username' => $_SERVER['SMTP_USUARIO'] ?? getenv('SMTP_USUARIO'),
                'password' => $_SERVER['SMTP_SENHA'] ?? getenv('SMTP_SENHA'),
                'port' => $_SERVER['SMTP_PORTA'] ?? getenv('SMTP_PORTA'),
                'encryption' => 'tls', // Ativação obrigatória para servidores Microsoft 365
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                    ],
                ],
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => [
                    ($_SERVER['EMAIL_ENVIO'] ?? 'seu_email_configurado@suaprefeitura.mg.gov.br') 
                    => 
                    ($_SERVER['NOME_REMETENTE'] ?? 'SisPMPI')
                ]
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

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
