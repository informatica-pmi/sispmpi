<?php

namespace app\modules\monitorar;

use yii\base\BootstrapInterface;

/**
 * monitorar module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\monitorar\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'pattern' => 'monitorar/<controller:(recomendacao)>/<action:(index)>/<filter:\d+>',
                'route' => 'monitorar/<controller>/<action>',
                'defaults' => ['filter' => '']
            ],
            'monitorar/<controller:(plano-integridade-novo)>/<action:(create)>/<tipoId:\d+>' => 'monitorar/<controller>/<action>',
            'monitorar/<folder:(reuniao)>/<controller:(pdf)>/<action:(index)>/<reuniaoId:\d+>' => 'monitorar/<folder>/<controller>/<action>',
            'monitorar/<folder:(acao)>/<controller:(default|monitoramento)>/<action:(view|create)>/<acaoId:\d+>' => 'monitorar/<folder>/<controller>/<action>'
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
