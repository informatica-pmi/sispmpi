<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use dosamigos\chartjs\ChartJs;
use app\components\helpers\Universal;
use app\models\Acao;

/* @var $this yii\web\View */
/* @var $modelPlano app\models\PlanoIntegridade */
/* @var $userOrgaoNome */
/* @var $dateConclusion */
/* @var $lastDateModified */
/* @var $chartStatus */
/* @var $chartRecomendacaos */
/* @var $pontosAtencao */
/* @var $isObservador */
/* @var $searchModelAcao app\models\pesquisa\AcaoSearch */
/* @var $existsPlanoIntegridadeNovo */

$this->title = 'Monitoramento das ações do programa de integridade';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="monitorar-default-index">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <header class="mb-3 pb-3 border-bottom">
                <h5><?= $userOrgaoNome ?></h5>

                <small class="d-block">
                    <?= sprintf(
                        'Versão do programa de integridade: %s - Versão %s',
                        $modelPlano->edicao,
                        number_format($modelPlano->versao, 2, '.', '')
                    ) ?>
                </small>

                <small class="d-block">
                    <?= sprintf('Concluída em: %s', $dateConclusion) ?>
                </small>

                <small class="d-block">
                    <?= sprintf('Data da última atualização do módulo de monitoramento: %s', $lastDateModified) ?>
                </small>
            </header>

            <?php if ($isObservador) : ?>
                <?= $this->render('../recomendacao/_search', [
                    'model' => $searchModelAcao,
                    'isObservador' => $isObservador,
                ]) ?>
            <?php endif; ?>

            <section id="charts" class="mb-3 pb-3 border-bottom">
                <h6 class="text-center mb-3">Painel de monitoramento</h6>

                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="card mb-0">
                            <div class="card-header text-center">Status das ações</div>

                            <div class="card-body">
                                <div class="d-flex flex-column flex-lg-row align-items-center justify-content-around">
                                    <div class="legend mb-3 mb-lg-0">
                                        <p class="mb-1 small text-muted">
                                            <?= Universal::icon('fas fa-circle text-primary mr-1') ?>
                                            Não inicializada
                                        </p>

                                        <p class="mb-1 small text-muted">
                                            <i class="fas fa-circle mr-1" style="color: #FF499E"></i>
                                            Em andamento
                                        </p>

                                        <p class="mb-1 small text-muted">
                                            <i class="fas fa-circle mr-1" style="color: #23CE6B"></i>
                                            Concluída
                                        </p>

                                        <p class="mb-1 small text-muted">
                                            <i class="fas fa-circle mr-1" style="color: #6f42c1"></i>
                                            Descontinuada
                                        </p>
                                    </div>

                                    <div>
                                        <?= ChartJs::widget([
                                            'type' => 'doughnut',
                                            'id' => 'chartStatus',
                                            'options' => [
                                                'height' => 200,
                                                'width' => 200,
                                            ],
                                            'data' => [
                                                'labels' => [
                                                    "Não inicializada",
                                                    "Em andamento",
                                                    "Concluída",
                                                    "Descontinuada"
                                                ],
                                                'datasets' => [
                                                    [
                                                        'datalabels' => [
                                                            'anchor' => 'start',
                                                            'color' => '#fff',
                                                            'backgroundColor' => new JsExpression("function (context) {
                                                                return context.dataset.backgroundColor[context.dataIndex];
                                                            }"),
                                                            'borderColor' => '#fff',
                                                            'borderWidth' => 2,
                                                            'borderRadius' => 50,
                                                            'display' => new JsExpression("function (context) {
                                                                return context.dataset.data[context.dataIndex] != 0;
                                                            }"),
                                                            'font' => [
                                                                'weight' => 'bold',
                                                            ]
                                                        ],
                                                        'data' => [
                                                            $chartStatus['naoInicializada'],
                                                            $chartStatus['emAndamento'],
                                                            $chartStatus['concluida'],
                                                            $chartStatus['descontinuada']
                                                        ],
                                                        'backgroundColor' => [
                                                            '#0D6EFD',
                                                            '#FF499E',
                                                            '#23CE6B',
                                                            '#6f42c1'
                                                        ],
                                                        'borderColor' => '#fff',
                                                        'borderWidth' => 0,
                                                    ],
                                                ]
                                            ],
                                            'clientOptions' => [
                                                'cutoutPercentage' => 60,
                                                'layout' => [
                                                    'padding' => [
                                                        'top' => 10,
                                                    ],
                                                ],
                                                'legend' => false,
                                                'onHover' => new JsExpression("function(element) {
                                                    chartStatus.style.cursor = 'pointer';
                                                }"),
                                                'onClick' => new JsExpression("function (elemen) {
                                                    let index = chartJS_chartStatus.getElementsAtEvent(elemen)[0]._index;

                                                    switch (index) {
                                                        case 0:
                                                            window.location = 'monitorar/recomendacao/index/4';
                                                            break;
                                                        case 1:
                                                            window.location = 'monitorar/recomendacao/index/5';
                                                            break;
                                                        case 2:
                                                            window.location = 'monitorar/recomendacao/index/6';
                                                            break;
                                                        case 3:
                                                            window.location = 'monitorar/recomendacao/index/7';
                                                            break;
                                                    }
                                                }"),
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="card mb-0">
                            <div class="card-header text-center">Recomendações</div>

                            <div class="card-body d-block d-sm-flex card-chart-recomendacaos">
                                <div class="card-group flex-grow-1">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title float-none mb-3">Comissão de integridade</h5>

                                            <?= Html::a(
                                                Universal::icon('fas fa-external-link-alt mr-1') .
                                                    $chartRecomendacaos['monitoramento']['naoRespondidasTotal'] .
                                                        ' Não respondidas',
                                                [
                                                    '@monitorar/recomendacao/index',
                                                    'filter' => Acao::FILTER_MONITORAMENTO_RECOMENDACAO_N_RESPONDIDA
                                                ],
                                                ['class' => 'btn btn-link text-warning p-0']
                                            ) ?>

                                            <div class="progress rounded mb-3">
                                                <div
                                                    class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                                    role="progressbar"
                                                    style="width: <?= $chartRecomendacaos['monitoramento']['naoRespondidasPorcentagem'] ?>%"
                                                    aria-valuenow="<?= $chartRecomendacaos['monitoramento']['naoRespondidasPorcentagem'] ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                >
                                                    <?= $chartRecomendacaos['monitoramento']['naoRespondidasPorcentagem'] ?>%
                                                </div>
                                            </div>

                                            <?= Html::a(
                                                Universal::icon('fas fa-external-link-alt mr-1') .
                                                $chartRecomendacaos['monitoramento']['respondidasTotal'] . ' Respondidas',
                                                [
                                                    '@monitorar/recomendacao/index',
                                                    'filter' => Acao::FILTER_MONITORAMENTO_RECOMENDACAO_RESPONDIDA
                                                ],
                                                ['class' => 'btn btn-link text-success p-0']
                                            ) ?>

                                            <div class="progress rounded">
                                                <div
                                                    class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                                    role="progressbar"
                                                    style="width: <?= $chartRecomendacaos['monitoramento']['respondidasPorcentagem'] ?>%"
                                                    aria-valuenow="<?= $chartRecomendacaos['monitoramento']['respondidasPorcentagem'] ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                >
                                                    <?= $chartRecomendacaos['monitoramento']['respondidasPorcentagem'] ?>%
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title float-none mb-3">Controle interno</h5>

                                            <?= Html::a(
                                                Universal::icon('fas fa-external-link-alt mr-1') .
                                                $chartRecomendacaos['controleInterno']['naoRespondidasTotal'] .
                                                    ' Não respondidas',
                                                [
                                                    '@monitorar/recomendacao/index',
                                                    'filter' => Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_N_RESPONDIDA
                                                ],
                                                ['class' => 'btn btn-link text-warning p-0']
                                            ) ?>

                                            <div class="progress rounded mb-3">
                                                <div
                                                    class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                                                    role="progressbar"
                                                    style="width: <?= $chartRecomendacaos['controleInterno']['naoRespondidasPorcentagem'] ?>%"
                                                    aria-valuenow="<?= $chartRecomendacaos['controleInterno']['naoRespondidasPorcentagem'] ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                >
                                                </div>
                                            </div>

                                            <?= Html::a(
                                                Universal::icon('fas fa-external-link-alt mr-1') .
                                                $chartRecomendacaos['controleInterno']['respondidasTotal'] .
                                                    ' Respondidas',
                                                [
                                                    '@monitorar/recomendacao/index',
                                                    'filter' => Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_RESPONDIDA
                                                ],
                                                ['class' => 'btn btn-link text-success p-0']
                                            ) ?>

                                            <div class="progress rounded">
                                                <div
                                                    class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                                    role="progressbar"
                                                    style="width: <?= $chartRecomendacaos['controleInterno']['respondidasPorcentagem'] ?>%"
                                                    aria-valuenow="<?= $chartRecomendacaos['controleInterno']['respondidasPorcentagem'] ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="pontos-atencao" class="pb-3 mb-3 border-bottom">
                <h6 class="text-center mb-3">Pontos de atenção</h6>

                <div class="d-flex flex-column flex-md-row">
                    <?php
                    $defaultClassAnchor = 'btn btn-outline-danger border d-flex flex-column justify-content-center';

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações ainda não inicializadas que estão atrasadas em relação à data prevista para seu início',
                            ['class' => 'border-bottom mb-3 pb-3']
                        ) . Html::tag('h3', $pontosAtencao['atrasadasInicio'], ['class' => 'mb-0']),
                        ['@monitorar/recomendacao/index', 'filter' => Acao::FILTER_ATRASADA_INICIO_PREVISTO],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-md-0 mr-md-1"]
                    );

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações que estão atrasadas em relação à data prevista para sua conclusão',
                            ['class' => 'border-bottom mb-2 pb-2']
                        ) . Html::tag('h3', $pontosAtencao['atrasadasConclusao'], ['class' => 'mb-0']),
                        ['@monitorar/recomendacao/index', 'filter' => Acao::FILTER_ATRASADA_CONCLUSAO_PREVISTO],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-md-0 mx-md-1"]
                    );

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações classificadas como ações com risco de não implementação alto ou extremo',
                            ['class' => 'border-bottom mb-2 pb-2']
                        ) . Html::tag('h3', $pontosAtencao['naoImplementacao'], ['class' => 'mb-0']),
                        ['@monitorar/recomendacao/index', 'filter' => Acao::FILTER_RISCO_N_IMPLEMENTACAO_ALTO],
                        ['class' => "{$defaultClassAnchor} ml-md-1"]
                    );
                    ?>
                </div>
            </section>

            <?php if (!$isObservador) : ?>
                <section id="actions">
                    <h6 class="text-center mb-3">Ações</h6>

                    <div class="d-flex flex-column flex-lg-row">
                        <?php
                        $defaultClassAnchor = 'btn btn-outline-secondary border d-flex flex-column ' .
                            'align-items-center justify-content-center';

                        echo Html::a(
                            Universal::icon('fas fa-download fa-3x mb-3') .
                                Html::tag('span', 'Gerar relatórios'),
                            ['@monitorar/relatorio/default/index'],
                            ['class' => "{$defaultClassAnchor} mb-3 mb-lg-0 mr-lg-1"]
                        );

                        echo Html::a(
                            Universal::icon('fas fa-tasks fa-3x mb-2') .
                                Html::tag('span', 'Inserir recomendações para as ações do plano de integridade'),
                            ['@monitorar/recomendacao'],
                            ['class' => "{$defaultClassAnchor} mb-3 mb-lg-0 mx-lg-1"]
                        );

                        echo Html::a(
                            Universal::icon('fas fa-users fa-3x mb-2') .
                                Html::tag(
                                    'span',
                                    'Elaborar e visualizar registros de reuniões da comissão de integridade'
                                ),
                            ['@monitorar/reuniao/default'],
                            ['class' => "{$defaultClassAnchor} mb-3 mb-lg-0 mx-lg-1"]
                        );

                        echo Html::a(
                            Universal::icon('fas fa-hand-holding-heart fa-3x mb-2') .
                                Html::tag(
                                    'span',
                                    $countPlanoRecomendacaoWithoutAnswer ?
                                        "Consultar e responder as recomendações gerais do controle interno <span class='badge badge-danger'>{$countPlanoRecomendacaoWithoutAnswer}</span>" :
                                        "Consultar e responder as recomendações gerais do controle interno"
                                ),
                            ['@monitorar/plano-integridade-recomendacao/update'],
                            ['class' => "{$defaultClassAnchor} mb-3 mb-lg-0 mx-lg-1"],
                        );

                        echo Html::button(
                            Universal::icon('fas fa-sync fa-3x mb-2') .
                                Html::tag('span', 'Solicitar atualização do programa de integridade'),
                            [
                                'class' => "{$defaultClassAnchor} ml-lg-1",
                                'value' => Url::to(['@monitorar/plano-integridade-novo/index']),
                                'id' => 'modalSolicitarPlano',
                                'data-show' => 'modal-solicitar-plano',
                                'disabled' => $existsPlanoIntegridadeNovo ? true : false
                            ],
                        ) ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php

Universal::modal('Solicitar atualização do programa de integridade', 'modal-solicitar-plano', 'modal-xl');

$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
