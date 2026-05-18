<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=DB_HOST;dbname=DB_NOME',
    'username' => 'DB_USUARIO',
    'password' => 'DB_SENHA',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
