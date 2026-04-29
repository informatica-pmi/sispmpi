<?php

namespace app\modules\elaborar;

use yii\base\BootstrapInterface;

/**
 * elaborar module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\elaborar\controllers';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            'elaborar/<controller:(plano-acao-parcial|plano-integridade-parcial|publicacao)>/<action>/<planoId:\d+>' => 'elaborar/<controller>/<action>',
            'elaborar/<folder:(grupo|validacao|diagnostico)>/<controller:(default)>/<action>/<planoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(grupo)>/<controller:(instituido|servidor)>/<action:(create)>/<grupoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(grupo)>/<controller:(instituido|servidor)>/<action:(update)>/<grupoId:\d+>/<order:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(diagnostico)>/<controller:(info-estrategica|instrumento|resultado)>/<action>/<diagnosticoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(redacao)>/<controller:(eixo|subeixo|acao)>/<action:(create)>/<planoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(redacao)>/<controller:(eixo)>/<action:(update)>/<eixoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(redacao)>/<controller:(subeixo)>/<action:(update)>/<subeixoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            'elaborar/<folder:(redacao)>/<controller:(acao)>/<action:(update)>/<acaoId:\d+>' => 'elaborar/<folder>/<controller>/<action>',
            [
                'pattern' => 'elaborar/<folder:(redacao)>/<controller:(default)>/<action:(create)>/<planoId:\d+>/<key:\w+>/<updateId:\d+>',
                'route' => 'elaborar/<folder>/<controller>/<action>',
                'defaults' => ['key' => '', 'updateId' => ''],
            ]
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
