<?php

// comment out the following two lines when deployed to production
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

// Configuração para ambiente de Produção
defined('YII_DEBUG') or define('YII_DEBUG', false); // Muda para false
defined('YII_ENV') or define('YII_ENV', 'prod');    // Muda para prod

// === NOVO: Ignorar avisos de depreciação do PHP 8.2 ===
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
// =======================================================

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
