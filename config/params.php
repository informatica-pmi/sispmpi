<?php

use kartik\datecontrol\Module;

return [
    'user.passwordResetTokenExpire' => 3600,
    'bsVersion' => '4.x',
    'emailControlador' => 'EMAIL_LIVRE_DE_NOTIFICACOES_DO_SISTEMA',

    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'php:d/m/Y',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:d/m/Y H:i:s',
    ],

    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],

    'maskMoneyOptions' => [
        'prefix' => 'R$ ',
        'affixesStay' => true,
        'thousands' => '.',
        'decimal' => ',',
        'precision' => 2,
        'allowZero' => false,
    ],

    'darkIsActive' => isset($_COOKIE['dark']) && $_COOKIE['dark'] == 'true',
];
