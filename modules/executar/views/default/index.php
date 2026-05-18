<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\LinkPager;
use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;
use app\components\helpers\Semaphore;
use app\models\Acao;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelPlano app\models\PlanoIntegridade */
/* @var $userOrgaoNome */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\pesquisa\AcaoSearch */
/* @var $paginationAcao yii\data\Pagination */
/* @var $dateCompletion */
/* @var $lastDateModified */

$this->title = 'Execução das ações do plano de integridade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="executar-not-placeholder">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <header class="">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <h5><?= $userOrgaoNome ?></h5>

                    <?= Html::a(
                        Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                        Url::to(['@executar/pdf/index']),
                        [
                            'class' => 'btn btn-danger',
                            'target' => '_blank',
                            'rel' => 'noopener noreferrer'
                        ]
                    ) ?>
                </div>

                <small class="d-block">
                    <?= sprintf(
                        'Versão do programa de integridade: %s - Versão %s',
                        $modelPlano->edicao,
                        number_format($modelPlano->versao, 2, '.', '')
                    ) ?>
                </small>

                <small class="d-block">
                    <?= sprintf('Concluída em: %s', $dateCompletion) ?>
                </small>

                <small class="d-block">
                    <?= sprintf('Última revisão em: %s', $lastDateModified) ?>
                </small>
            </header>

            <section class="search">
                <hr>

                <h6><?= Universal::icon('fas fa-filter') ?> Filtros</h6>

                <?= $this->render('_search', [
                    'model' => $searchModel
                ]) ?>
            </section>

            <section class="list">
                <small class="text-muted"><?= $dataProvider->getTotalCount() ?> item(s) econtrados</small>

                <?php array_map(function (Acao $acao) { ?>
                    <div class="card">
                        <div class="card-header">
                            <?= sprintf('Ação %s: %s', $acao->numero, $acao->titulo) ?>
                        </div>

                        <div class="card-body">
                            <p class="mb-1">
                                <?= sprintf('Eixo: %s', $acao->eixo->titulo) ?>
                            </p>

                            <p class="mb-1">
                                <?= sprintf(
                                    'Sub-eixo: %s',
                                    Universal::valueField($acao->subeixo, 'titulo', true)
                                ) ?>
                            </p>

                            <p>
                                <?= sprintf('Unidade Adm. Responsável: %s', $acao->unidadeExecutora->nome) ?>
                            </p>

                            <div class="card mb-0">
                                <div class="card-body">
                                    <p class="font-weight-semi-bold"><u>Outras informações</u></p>

                                    <p class="mb-1">
                                        <?= sprintf('Classificação: %s', $acao->classificacao) ?>
                                    </p>

                                    <p class="mb-1">
                                        <?= sprintf(
                                            'Tipo: %s',
                                            implode(
                                                ", ",
                                                ArrayHelper::getColumn($acao->acaoTipos, 'tipo.nome')
                                            )
                                        ) ?>
                                    </p>

                                    <p class="mb-1">
                                        <?= sprintf('Status: %s', Status::getAcaoStatus($acao->status)) ?>
                                    </p>

                                    <p class="mb-1 d-flex align-items-center">
                                        Semáforo:
                                        <span class="dot <?= Semaphore::generate($acao) ?>"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <?= Html::a(
                                Universal::icon('far fa-file-alt') . ' Detalhes',
                                ['@executar/acao/default/view', 'acaoId' => $acao->id],
                                ['class' => 'btn btn-primary'],
                            ) ?>
                        </div>
                    </div>
                <?php }, $dataProvider->getModels()); ?>
            </section>

            <?= LinkPager::widget([
                'pagination' => $paginationAcao,
                'prevPageLabel' => false,
            ]); ?>
        </div>
    </div>
</div>
