<?php

namespace app\modules\executar;

use yii\base\BootstrapInterface;

/**
 * executar module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\executar\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'executar/<folder:(acao)>/<controller>/<action:(index|view|create|update)>/<acaoId:\d+>' => 'executar/<folder>/<controller>/<action>'
        ], false);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
