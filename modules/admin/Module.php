<?php

namespace app\modules\admin;

use yii\base\BootstrapInterface;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'admin/<controller:(informacao-estado|orgao)>/<action:(view|update)>/<id:\d+>' => 'admin/<controller>/<action>',
            [
                'pattern' => 'admin/<controller:(fator-limitante|instrumento|stakeholder)>/<action:(index)>/<page:\d+>/<id:\d+>',
                'route' => 'admin/<controller>/<action>',
                'defaults' => ['id' => '']
            ],
            'admin/<controller:(plano-integridade-reabertura)>/<action:(create)>/<planoId:\d+>' => 'admin/<controller>/<action>',
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
