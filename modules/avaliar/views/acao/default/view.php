<?php

use yii\helpers\Url;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
/* @var $modelAcaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao[] */
/* @var $modelAcaoAvaliacaoRecomendacaos app\models\AcaoAvaliacaoRecomendacao[] */
/* @var $userOrgaoNome */
/* @var $tipoNomes */
/* @var $unidadeApoioNomes */
/* @var $servidorResponsavelNome */
/* @var $servidorEnvolvidoNomes */
/* @var $fatorLimitanteNomes */

$this->title = 'Detalhes';
$this->params['breadcrumbs'][] = [
    'label' => 'Avaliação do programa de integridade',
    'url' => ['@avaliar']
];
$this->params['breadcrumbs'][] = ['label' => 'Recomendações', 'url' => Url::previous('avaliar-recomendacao')];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="acao-view">
    <div class="card card-outline card-secondary">
        <div class="card-body">
            <header class="mb-3">
                <h5 class="border-bottom pb-3 mb-3"><?= $userOrgaoNome ?></h5>

                <h6 class="font-weight-normal"><?= $modelAcao->titulo ?></h6>
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
                                'tipoNomes' => $tipoNomes,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="responsabilidade-acao">
                        <h5 class="mb-0">
                            <button
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
                            <?= $this->render('../responsabilidade/view', [
                                'modelAcao' => $modelAcao,
                                'unidadeApoioNomes' => $unidadeApoioNomes,
                                'servidorResponsavelNome' => $servidorResponsavelNome,
                                'servidorEnvolvidoNomes' => $servidorEnvolvidoNomes
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="execucao-acao">
                        <h5 class="mb-0">
                            <button
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
                            <?= $this->render('../execucao/view', [
                                'modelAcao' => $modelAcao,
                                'modelAcaoExecucao' => $modelAcaoExecucao,
                                'fatorLimitanteNomes' => $fatorLimitanteNomes,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="monitoramento-acao">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-monitoramento-acao"
                                aria-expanded="false"
                                aria-controls="collapse-monitoramento-acao"
                            >
                                Monitoramento da ação - Classificação de Risco e Recomendação da Comissão de Integridade
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
                                'modelAcaoMonitoramento' => $modelAcaoMonitoramento,
                                'modelsAcaoMonitoramentoRecomendacao' => $modelsAcaoMonitoramentoRecomendacao,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="avaliacao-acao">
                        <h5 class="mb-0">
                            <button
                                class="btn btn-link collapsed w-100 d-flex align-items-center justify-content-between"
                                data-toggle="collapse"
                                data-target="#collapse-avaliacao-acao"
                                aria-expanded="false"
                                aria-controls="collapse-avaliacao-acao"
                            >
                                Avaliação da Ação - Recomendações do Controle Interno
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
                                'modelAcaoAvaliacaoRecomendacaos' => $modelAcaoAvaliacaoRecomendacaos,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
