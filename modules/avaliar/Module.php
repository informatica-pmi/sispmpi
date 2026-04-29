<?php

namespace app\modules\avaliar;

use yii\base\BootstrapInterface;

/**
 * avaliar module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\avaliar\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'pattern' => 'avaliar/<controller:(recomendacao)>/<action:(index)>/<filter:\d+>',
                'route' => 'avaliar/<controller>/<action>',
                'defaults' => ['filter' => '']
            ],
            'avaliar/<folder:(acao)>/<controller:(default|avaliacao)>/<action:(view|create)>/<acaoId:\d+>' => 'avaliar/<folder>/<controller>/<action>',
            'avaliar/<folder:(promover)>/<controller:(default)>/<action:(update)>/<id:\d+>' => 'avaliar/<folder>/<controller>/<action>',
            'avaliar/<folder:(reuniao)>/<controller:(pdf)>/<action:(index)>/<reuniaoId:\d+>' => 'avaliar/<folder>/<controller>/<action>'
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
