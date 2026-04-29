<?php

/* @var $this yii\web\View */
/* @var $modelsPlano app\models\PlanoIntegridade[] */

use yii\bootstrap4\ButtonDropdown;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;

$this->title = 'Versões anteriores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditor-default-index">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <p class="card-text mb-3 pb-3 border-bottom">
                Nesta tela é apresentada a listagem de todos os programas de integridade já alimentados no sistema,
                possibilitando que a unidade de controle interno emita relatórios específicos das versões ou edições
                anteriores. Esta funcionalidade é exclusiva para usuários cadastrados com o perfil 'Auditor'.
            </p>

            <section class="list">
                <small class="text-muted">
                    <?= sprintf('%s item(s) encontrados', count($modelsPlano)) ?>
                </small>

                <?php array_map(function (PlanoIntegridade $plano) { ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">
                                <?= sprintf(
                                    '%s - Versão %s',
                                    $plano->edicao,
                                    number_format($plano->versao, 2, '.', '')
                                ) ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <?= ButtonDropdown::widget([
                                'label' => Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                                'encodeLabel' => false,
                                'buttonOptions' => ['class' => 'btn btn-danger btn-sm'],
                                'dropdown' => [
                                    'items' => [
                                        [
                                            'label' => 'Plano de integridade publicado',
                                            'url' => [
                                                '/arquivo/download',
                                                'token' => $plano->publicacao->planoIntegridadeArquivo->token
                                            ],
                                        ],
                                        [
                                            'label' => 'Plano de ação consolidado',
                                            'url' => !empty($plano->publicacao->planoAcaoArquivo) ?
                                                [
                                                    '/arquivo/download',
                                                    'token' => $plano->publicacao->planoAcaoArquivo->token
                                                ] :
                                                '#',
                                            'visible' => !empty($plano->publicacao->planoAcaoArquivo),
                                        ],
                                        [
                                            'label' => 'Relatório de monitoramento',
                                            'url' => [
                                                '@auditor/relatorio/monitoramento/index',
                                                'planoId' => $plano->id
                                            ],
                                            'linkOptions' => ['target' => '_blank', 'rel' => 'noreferrer noopener']
                                        ],
                                        [
                                            'label' => 'Relatório com histórico de alterações realizadas',
                                            'url' => ['@auditor/relatorio/historico/index', 'planoId' => $plano->id],
                                            'linkOptions' => ['target' => '_blank', 'rel' => 'noreferrer noopener']
                                        ],
                                        [
                                            'label' => 'Relatório do controle interno',
                                            'url' => [
                                                '@auditor/relatorio/controle-interno/index',
                                                'planoId' => $plano->id
                                            ],
                                            'linkOptions' => ['target' => '_blank', 'rel' => 'noreferrer noopener']
                                        ],
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                <?php }, $modelsPlano)?>
            </section>
        </div>
    </div>
</div>
