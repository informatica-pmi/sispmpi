<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use dosamigos\chartjs\ChartJs;
use app\components\helpers\Universal;
use app\models\Acao;

/* @var $modelPlano app\models\PlanoIntegridade */
/* @var $userOrgaoNome */
/* @var $chartStatus */
/* @var $chartRecomendacaos */
/* @var $pontosAtencao */
/* @var $planoIntegridadeRecomendationIsCreate */
/* @var $dateConclusion */
/* @var $lastDateModified */
/* @var $existsPlanoIntegridadeNovo */
/* @var $disabledAnchorPlanoElaboracao */

$this->title = 'Avaliação do programa de integridade';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="avaliar-default-index">
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
                    <?= sprintf('Data da última atualização do módulo de avaliação: %s', $lastDateModified) ?>
                </small>
            </header>

            <section id="charts" class="mb-3 pb-3 border-bottom">
                <h6 class="text-center mb-3">Painel de monitoramento</h6>

                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="card mb-0">
                            <div class="card-header text-center">Status das Ações</div>
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
                                                            window.location = 'avaliar/recomendacao/index/4';
                                                            break;
                                                        case 1:
                                                            window.location = 'avaliar/recomendacao/index/5';
                                                            break;
                                                        case 2:
                                                            window.location = 'avaliar/recomendacao/index/6';
                                                            break;
                                                        case 3:
                                                            window.location = 'avaliar/recomendacao/index/7';
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
                                                    '@avaliar/recomendacao/index',
                                                    'filter' => Acao::FILTER_MONITORAMENTO_RECOMENDACAO_N_RESPONDIDA
                                                ],
                                                ['class' => "btn btn-link text-warning text-left p-0 {$disabledAnchorPlanoElaboracao}"]
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
                                                    '@avaliar/recomendacao/index',
                                                    'filter' => Acao::FILTER_MONITORAMENTO_RECOMENDACAO_RESPONDIDA
                                                ],
                                                ['class' => "btn btn-link text-success text-left p-0 {$disabledAnchorPlanoElaboracao}"]
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
                                                    '@avaliar/recomendacao/index',
                                                    'filter' => Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_N_RESPONDIDA
                                                ],
                                                ['class' => "btn btn-link text-warning text-left p-0 {$disabledAnchorPlanoElaboracao}"]
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
                                                    <?= $chartRecomendacaos['controleInterno']['naoRespondidasPorcentagem'] ?>%
                                                </div>
                                            </div>

                                            <?= Html::a(
                                                Universal::icon('fas fa-external-link-alt mr-1') .
                                                $chartRecomendacaos['controleInterno']['respondidasTotal'] .
                                                    ' Respondidas',
                                                [
                                                    '@avaliar/recomendacao/index',
                                                    'filter' => Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_RESPONDIDA
                                                ],
                                                ['class' => "btn btn-link text-success text-left p-0 {$disabledAnchorPlanoElaboracao}"]
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
                                                    <?= $chartRecomendacaos['controleInterno']['respondidasPorcentagem'] ?>%
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
                    $defaultClassAnchor = "btn btn-outline-danger border d-flex flex-column justify-content-center {$disabledAnchorPlanoElaboracao}";

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações ainda não inicializadas que estão atrasadas em relação à data prevista para seu início',
                            ['class' => 'border-bottom mb-3 pb-3']
                        ) . Html::tag('h3', $pontosAtencao['atrasadasInicio'], ['class' => 'mb-0']),
                        ['@avaliar/recomendacao/index', 'filter' => Acao::FILTER_ATRASADA_INICIO_PREVISTO],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-md-0 mr-md-1"]
                    );

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações que estão atrasadas em relação à data de conclusão',
                            ['class' => 'border-bottom mb-3 pb-3']
                        ) . Html::tag('h3', $pontosAtencao['atrasadasConclusao'], ['class' => 'mb-0']),
                        ['@avaliar/recomendacao/index', 'filter' => Acao::FILTER_ATRASADA_CONCLUSAO_PREVISTO],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-md-0 mx-md-1"]
                    );

                    echo Html::a(
                        Html::tag(
                            'span',
                            'Ações classificadas como ações com risco de não implementação alto e extremo',
                            ['class' => 'border-bottom mb-3 pb-3']
                        ) . Html::tag('h3', $pontosAtencao['naoImplementacao'], ['class' => 'mb-0']),
                        ['@avaliar/recomendacao/index', 'filter' => Acao::FILTER_RISCO_N_IMPLEMENTACAO_ALTO],
                        ['class' => "{$defaultClassAnchor} ml-md-1"]
                    );
                    ?>
                </div>
            </section>

            <section id="actions">
                <h6 class="text-center mb-3">Ações</h6>

                <div class="d-flex flex-column flex-xl-row">
                    <?php
                    $defaultClassAnchor = "btn btn-outline-secondary border d-flex flex-column justify-content-center align-items-center {$disabledAnchorPlanoElaboracao}";
                    $disableAnchorNovoPlano = $existsPlanoIntegridadeNovo ? '' : 'disabled';

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-download fa-3x mb-3']) .
                        Html::tag('span', ' Gerar Relatórios'),
                        ['@avaliar/relatorio/default'],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-xl-0 mr-xl-1"]
                    );

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-tasks fa-3x mb-3']) .
                        Html::tag('span', ' Inserir recomendações para as ações do plano de integridade'),
                        ['@avaliar/recomendacao/index'],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-xl-0 mx-xl-1"]
                    );

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-users fa-3x mb-3']) .
                        Html::tag('span', ' Visualizar registro de reuniões da comissão de integridade'),
                        ['@avaliar/reuniao/default'],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-xl-0 mx-xl-1"]
                    );

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-hand-holding-heart fa-3x mb-3']) .
                        Html::tag('span', ' Inserir recomendações gerais à comissão de integridade'),
                        [
                            $planoIntegridadeRecomendationIsCreate ?
                                '@avaliar/plano-integridade-recomendacao/create' :
                                '@avaliar/plano-integridade-recomendacao/update'
                        ],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-xl-0 mx-xl-1"]
                    );

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-comments fa-3x mb-3']) .
                        Html::tag(
                            'span',
                            ' Registro das ações de promoção da integridade realizadas pela unidade de controle ' .
                            'interno da organização',
                        ),
                        ['@avaliar/promover/default'],
                        ['class' => "btn btn-outline-secondary border d-flex flex-column justify-content-center align-items-center mb-3 mb-xl-0 mx-xl-1"]
                    );

                    echo Html::a(
                        Html::tag('i', '', ['class' => 'fas fa-comments fa-3x mb-3']) .
                        Html::tag(
                            'span',
                            $existsPlanoIntegridadeNovo ?
                                ' Autorizar a revisão ou atualização do programa de integridade <span class="badge badge-danger">1</span>' :
                                ' Autorizar a revisão ou atualização do programa de integridade'
                        ),
                        ['@avaliar/plano-integridade-novo/update'],
                        ['class' => "{$defaultClassAnchor} mb-3 mb-xl-0 mx-xl-1 {$disableAnchorNovoPlano}"]
                    );
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php

$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
