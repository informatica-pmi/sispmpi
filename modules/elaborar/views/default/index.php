<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $modelPlano app\models\PlanoIntegridade */
/* @var $preparePermissions */

$this->title = 'Elaboração do programa de integridade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elaborar-index">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main-timeline">
                    <div class="timeline-item <?= $preparePermissions['hasDefault'] ?>">
                        <a
                            href=<?= Url::to([
                                $modelPlano->grupo ?
                                    '@elaborar/grupo/default/update' :
                                    '@elaborar/grupo/default/create',
                                'planoId' => $modelPlano->id
                            ]) ?>
                            class="timeline-content"
                        >
                            <div class="timeline-number">1</div>

                            <h3 class="title">Instituição da Comissão de Integridade</h3>

                            <p class="description">
                                Inserção de informações sobre a comissão de integridade e sobre o ato normativo que a
                                instituiu, assim como o número de dias previstos para a conclusão do processo de
                                formulação.
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasDefault'] ?>">
                        <a
                            href=<?= Url::to([
                                $modelPlano->diagnostico ?
                                    '@elaborar/diagnostico/default/update' :
                                    '@elaborar/diagnostico/default/create',
                                'planoId' => $modelPlano->id
                            ]) ?>
                            class="timeline-content"
                        >
                            <div class="timeline-number">2</div>

                            <h3 class="title">Programa de Integridade</h3>

                            <p class="description">
                                Estruturação do programa de integridade, realizada a partir do diagnóstico prévio do
                                ambiente de integridade da organização.
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasDefault'] ?>">
                        <a
                            href=<?= Url::to(['@elaborar/redacao/default/create', 'planoId' => $modelPlano->id]) ?>
                            class="timeline-content"
                        >
                            <div class="timeline-number">3</div>

                            <h3 class="title">Plano de Integridade</h3>

                            <p class="description">
                                Estruturação do plano de ação, organizado em eixos temáticos e ações compatíveis com a
                                visão e os objetivos do órgão ou da entidade em relação ao ambiente de integridade.
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasValidacao'] ?>">
                        <a
                            href=<?= Url::to([
                                $modelPlano->validacao ?
                                    '@elaborar/validacao/default/update' :
                                    '@elaborar/validacao/default/create',
                                'planoId' => $modelPlano->id
                            ]) ?>
                            class="timeline-content"
                        >
                            <div class="timeline-number">4</div>

                            <h3 class="title">Validação Geral</h3>

                            <p class="description">
                                Inserção de informações sobre o processo de validação do programa e do plano de
                                integridade da organização.
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasIntegridade'] ?>">
                        <a
                            href=<?= Url::to(
                                $modelPlano->publicacao ?
                                Yii::getAlias("@web/{$modelPlano->publicacao->planoIntegridadeArquivo->path}") :
                                ['@elaborar/plano-integridade-parcial/index', 'planoId' => $modelPlano->id]
                            ) ?>
                            class="timeline-content"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div class="timeline-number">5</div>

                            <h3 class="title">
                                <?= $modelPlano->publicacao ?
                                    'Programa e plano de integridade publicado' :
                                    'Minuta do Programa de Integridade'
                                ?>
                            </h3>

                            <p class="description">
                                <?= $modelPlano->publicacao ?
                                    'Acesse o arquivo com a versão atual do programa e do plano de integridade da
                                        organização.' :
                                    'Emissão de documento de texto editável do programa de integridade, para revisão
                                        textual, validação pelas partes interessadas e diagramação.'
                                ?>
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasAcao'] ?>">
                        <a
                            href=<?= Url::to($preparePermissions['hasAcaoFile'] ?
                                Yii::getAlias("@web/{$modelPlano->publicacao->planoAcaoArquivo->path}") :
                                ['@elaborar/plano-acao-parcial/index', 'planoId' => $modelPlano->id])
                                                ?>
                            class="timeline-content"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div class="timeline-number">6</div>

                            <h3 class="title">
                                <?= $preparePermissions['hasAcaoFile'] ?
                                    'Plano de ação consolidado' :
                                    'Minuta do Plano de Integridade'
                                ?>
                            </h3>

                            <p class="description">
                                <?= $preparePermissions['hasAcaoFile'] ?
                                    'Plano de ação consolidado após publicação. Acesse o documento final.' :
                                    'Emissão de planilha eletrônica editável do plano de integridade, para revisão
                                        textual, validação pelas partes interessadas e diagramação.'
                                ?>
                            </p>
                        </a>
                    </div>
                    <div class="timeline-item <?= $preparePermissions['hasPublicacao'] ?>">
                        <a
                            href=<?= Url::to(['@elaborar/publicacao/create', 'planoId' => $modelPlano->id]) ?>
                            class="timeline-content"
                        >
                            <div class="timeline-number">7</div>

                            <h3 class="title">Publicação do programa e do plano de integridade</h3>

                            <p class="description">
                                Inserção da versão final do programa e do plano de integridade e das informações sobre a
                                sua publicação e lançamento.
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCssFile('@web/css/timeline.css');
