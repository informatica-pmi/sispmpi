<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => 'ENDERECO_DA_APLICACAO',
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $_SERVER['SMTP_HOST'] ?? getenv('SMTP_HOST'),
                'username' => $_SERVER['SMTP_USUARIO'] ?? getenv('SMTP_USUARIO'),
                'password' => $_SERVER['SMTP_SENHA'] ?? getenv('SMTP_SENHA'),
                'port' => $_SERVER['SMTP_PORTA'] ?? getenv('SMTP_PORTA'),
                'encryption' => 'tls',
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,

    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            //'migrationPath' => null,
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],
        /* 'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ], */
    ],

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
