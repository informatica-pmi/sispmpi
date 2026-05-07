<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=" . getenv('DB_HOST') . ";port=" . getenv('DB_PORT') . ";dbname=" . getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => 'utf8',

    // Configurações críticas para Produção:
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600, // 1 hora
    'schemaCache' => 'cache',
];
