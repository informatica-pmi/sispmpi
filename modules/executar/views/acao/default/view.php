<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelServidor app\models\Servidor */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $modelAcaoExecucao app\models\AcaoExecucao */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao[] */
/* @var $modelsAcaoAvaliacaoRecomendacao app\models\AcaoAvaliacaoRecomendacao[] */
/* @var $acaoExecucaoEvidenciaFiles app\modules\executar\models\AcaoExecucaoArquivo[] */
/* @var $acaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
/* @var $optionsTipo app\models\Tipo[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $optionsFatorLimitante app\models\FatorLimitante[] */
/* @var $prepareAccordions */
/* @var $userOrgaoNome */
/* @var $disabledFieldAcaoExecucaoEvidenciaFiles */

$this->title = 'Detalhes';
$this->params['breadcrumbs'][] = [
    'label' => 'Execução das ações do plano de integridade',
    'url' => ['@executar']
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="acao-view">
    <div class="card card-outline card-secondary">
        <div class="card-body">
            <header class="mb-3">
                <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <h5 class="mb-lg-0"><?= $userOrgaoNome ?></h5>

                    <div class="actions">
                        <?php
                        echo Html::a(
                            Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                            Url::to(['@executar/acao/pdf/index', 'acaoId' => $modelAcao->id]),
                            [
                                'class' => 'btn btn-danger',
                                'target' => '_blank',
                                'rel' => 'noopener noreferrer'
                                ]
                        );

                        if (!is_null($modelAcao->acao_referencia_id)) :
                            echo Html::a(
                                Universal::icon('far fa-copy') . ' Copiar os dados da versão anterior',
                                Url::to(['@executar/acao/copiar/update', 'acaoId' => $modelAcao->id]),
                                [
                                'class' => 'btn btn-link text-success py-0 text-left',
                                'data-confirm' => 'Tem certeza que deseja realizar esta ação? ' .
                                    'ESTE PROCEDIMENTO É IRREVERSÍVEL!'
                                ]
                            );
                        endif;
                        ?>
                    </div>
                </div>

                <h6 class="font-weight-normal">
                    <?= sprintf('Ação: %s', $modelAcao->titulo) ?>
                </h6>
            </header>

            <div id="accordion-acao">
                <div class="card">
                    <div class="card-header" id="informacoes-gerais">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-informacoes-gerais"
                                aria-expanded="true"
                                aria-controls="collapse-informacoes-gerais"
                            >
                                Informações Gerais da Ação
                                <?= Universal::icon('fas fa-angle-double-up') ?>
                            </button>
                        </h5>
                    </div>

                    <div
                        id="collapse-informacoes-gerais"
                        class="collapse"
                        aria-labelledby="informacoes-gerais"
                        data-parent="#accordion-acao"
                    >
                        <div class="card-body">
                            <?= $this->render('../informacao/view', [
                                'modelAcao' => $modelAcao,
                            ]) ?>

                            <?= $this->render('../informacao/_form', [
                                'modelAcao' => $modelAcao,
                                'optionsTipo' => $optionsTipo,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="responsabilidade-acao">
                        <h5 class="mb-0">
                            <button
                                <?= $prepareAccordions['responsabilidade'] ? 'disabled' : '' ?>
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-responsabilidade-acao"
                                aria-expanded="false"
                                aria-controls="collapse-responsabilidade-acao"
                            >
                                Responsabilidade pela Ação
                                <?= Universal::icon('fas fa-angle-double-up') ?>
                            </button>
                        </h5>
                    </div>

                    <div
                        id="collapse-responsabilidade-acao"
                        class="collapse"
                        aria-labelledby="responsabilidade-acao"
                        data-parent="#accordion-acao"
                    >
                        <div class="card-body">
                            <?= $this->render('../responsabilidade/_form', [
                                'modelAcao' => $modelAcao,
                                'modelServidor' => $modelServidor,
                                'modelsServidor' => $modelsServidor,
                                'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="execucao-acao">
                        <h5 class="mb-0">
                            <button
                                <?= $prepareAccordions['execucao'] ? 'disabled' : '' ?>
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-execucao-acao"
                                aria-expanded="false"
                                aria-controls="collapse-execucao-acao"
                            >
                                Execução da Ação
                                <?= Universal::icon('fas fa-angle-double-up') ?>
                            </button>
                        </h5>
                    </div>

                    <div
                        id="collapse-execucao-acao"
                        class="collapse"
                        aria-labelledby="execucao-acao"
                        data-parent="#accordion-acao"
                    >
                        <div class="card-body">
                            <?= $this->render('../execucao/_form', [
                                'modelAcao' => $modelAcao,
                                'modelAcaoExecucao' => $modelAcaoExecucao,
                                'acaoExecucaoEvidenciaFiles' => $acaoExecucaoEvidenciaFiles,
                                'disabledFieldAcaoExecucaoEvidenciaFiles' => $disabledFieldAcaoExecucaoEvidenciaFiles,
                                'optionsFatorLimitante' => $optionsFatorLimitante,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="monitoramento-acao">
                        <h5 class="mb-0">
                            <button
                                <?= $prepareAccordions['monitoramento'] ? 'disabled' : '' ?>
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-monitoramento-acao"
                                aria-expanded="false"
                                aria-controls="collapse-monitoramento-acao"
                            >
                                Monitoramento da Ação - Classificação de Risco e Recomendações da Comissão de Integridade
                                <?= Universal::icon('fas fa-angle-double-up') ?>
                            </button>
                        </h5>
                    </div>

                    <div
                        id="collapse-monitoramento-acao"
                        class="collapse"
                        aria-labelledby="monitoramento-acao"
                        data-parent="#accordion-acao"
                    >
                        <div class="card-body">
                            <?= $this->render('../monitoramento/view', [
                                'acaoMonitoramento' => $acaoMonitoramento,
                            ]) ?>

                            <?= $this->render('../monitoramento/_form', [
                                'modelAcao' => $modelAcao,
                                'modelsAcaoMonitoramentoRecomendacao' => $modelsAcaoMonitoramentoRecomendacao,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="avaliacao-acao">
                        <h5 class="mb-0">
                            <button
                                <?= $prepareAccordions['avaliacao'] ? 'disabled' : '' ?>
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-avaliacao-acao"
                                aria-expanded="false"
                                aria-controls="collapse-avaliacao-acao"
                            >
                                Avaliação da Ação - Recomendação do Controle Interno
                                <?= Universal::icon('fas fa-angle-double-up') ?>
                            </button>
                        </h5>
                    </div>

                    <div
                        id="collapse-avaliacao-acao"
                        class="collapse"
                        aria-labelledby="avaliacao-acao"
                        data-parent="#accordion-acao"
                    >
                        <div class="card-body">
                            <?= $this->render('../avaliacao/_form', [
                                'modelAcao' => $modelAcao,
                                'modelsAcaoAvaliacaoRecomendacao' => $modelsAcaoAvaliacaoRecomendacao,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
