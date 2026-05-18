<?php

use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\components\helpers\Universal;
use app\models\Historico;
use yii\bootstrap4\ButtonDropdown;

/* @var $this yii\web\View */

$this->title = 'Gerar relatórios';
$this->params['breadcrumbs'][] = ['label' => 'Avaliação do programa de integridade', 'url' => ['@avaliar']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="relatorio-default-not-placeholder">
    <div class="accordion" id="accordionRelatorio">
        <div class="card">
            <div class="card-header" id="headingRelatorioMonitoramento">
                <h2 class="mb-0">
                    <button
                        class="btn btn-link btn-block d-flex align-items-center justify-content-between"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapseRelatorioMonitoramento"
                        aria-expanded="true"
                        aria-controls="collapseRelatorioMonitoramento"
                    >
                        Relatório de monitoramento
                        <?= Universal::icon('fas fa-angle-double-up') ?>
                    </button>
                </h2>
            </div>

            <div
                id="collapseRelatorioMonitoramento"
                class="collapse show"
                aria-labelledby="#headingRelatorioMonitoramento"
                data-parent="#accordionRelatorio"
            >
                <div class="card-body border-bottom">
                    <p class="card-text text-justify">
                        O relatório de monitoramento contém informações sobre a situação de todas as ações do plano de
                        integridade da organização, inclusive as recomendações realizadas pela comissão de integridade
                        ou pela unidade de controle interno (e suas respectivas respostas).
                    </p>

                    <p class="card-text">
                        <i>
                            Para gerar um relatório parcial é necessário selecionar as ações no campo abaixo.
                        </i>
                    </p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-relatorio-monitoramento',
                        'action' => ['@avaliar/relatorio/monitoramento/index'],
                        'method' => 'get',
                        'options' => ['target' => '_blank'],
                    ]) ?>

                    <?= $form->field($searchModelHistorico, 'acaoIds')->widget(Select2::className(), [
                        'data' => $optionsAcao,
                        'theme' => Select2::THEME_KRAJEE,
                        'showToggleAll' => false,
                        'options' => ['id' => 'acaoIdsMonitoramento', 'multiple' => true],
                        'pluginOptions' => ['allowClear' => true]
                    ])->label('Ações') ?>

                    <?= Html::submitButton(
                        Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                        ['class' => 'btn btn-danger']
                    ) ?>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingRelatorioHistorico">
                <h2 class="mb-0">
                    <button
                        class="btn btn-link btn-block collapsed d-flex align-items-center justify-content-between"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapseRelatorioHistorico"
                        aria-expanded="true"
                        aria-controls="collapseRelatorioHistorico"
                    >
                        Histórico de alterações
                        <?= Universal::icon('fas fa-angle-double-up') ?>
                    </button>
                </h2>
            </div>

            <div
                id="collapseRelatorioHistorico"
                class="collapse"
                aria-labelledby="headingRelatorioHistorico"
                data-parent="#accordionRelatorio"
            >
                <div class="card-body border-bottom">
                    <p class="card-text text-justify">
                        O relatório com o histórico de alterações contém o registro de todas as alterações realizadas
                        em cada uma das ações do plano de integridade da organização, informando o usuário, a data e o
                        horário de alteração.
                    </p>

                    <p class="card-text">
                        <i>
                            Para gerar um relatório parcial é necessário selecionar as datas e/ou as ações nos campos
                            abaixo.
                        </i>
                    </p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-relatorio-historico',
                        'action' => ['@avaliar/relatorio/historico/index'],
                        'method' => 'get',
                        'options' => ['target' => '_blank'],
                    ]) ?>

                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($searchModelHistorico, 'data_inicio')->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE,
                                'widgetOptions' => [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'orientation' => 'top auto',
                                    ]
                                ]
                            ])->label('Data de início') ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($searchModelHistorico, 'data_fim')->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE,
                                'widgetOptions' => [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'orientation' => 'top auto',
                                    ]
                                ]
                            ])->label('Data de término') ?>
                        </div>
                    </div>

                    <?= $form->field($searchModelHistorico, 'acaoIds')->widget(Select2::className(), [
                        'data' => $optionsAcao,
                        'theme' => Select2::THEME_KRAJEE,
                        'showToggleAll' => false,
                        'options' => ['id' => 'acaoIdsHistorico', 'multiple' => true],
                        'pluginOptions' => ['allowClear' => true]
                    ])->label('Ações') ?>

                    <?= Html::submitButton(
                        Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                        ['class' => 'btn btn-danger']
                    ) ?>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>

        <div class="card border-bottom-0">
            <div class="card-header">
                <h2 class="mb-0">
                    <button
                        class="btn btn-link btn-block collapsed d-flex align-items-center justify-content-between"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapseRelatorioControleInterno"
                        aria-expanded="true"
                        aria-controls="collapseRelatorioControleInterno"
                    >
                        Relatório do controle interno
                        <?= Universal::icon('fas fa-angle-double-up') ?>
                    </button>
                </h2>
            </div>

            <div
                id="collapseRelatorioControleInterno"
                class="collapse"
                aria-labelledby="headingControleInterno"
                data-parent="#accordionRelatorio"
            >
                <div class="card-body border-bottom">
                    <p class="card-text text-justify">
                        O relatório do controle interno apresenta todas as recomendações (e as respectivas respostas)
                        realizadas pela unidade de controle interno às unidades administrativas responsáveis pela
                        execução das ações do plano de integridade e à comissão de integridade, assim como todos os
                        registros de ações de promoção da integridade realizadas pela unidade de controle interno da
                        organização.
                    </p>

                    <p class="card-text">
                        <i>
                            Para gerar um relatório parcial é necessário selecionar as datas e/ou as ações nos campos
                            abaixo.
                        </i>
                    </p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-relatorio-controle-interno',
                        'action' => ['@avaliar/relatorio/controle-interno/index'],
                        'method' => 'get',
                        'options' => ['target' => '_blank'],
                    ]) ?>

                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($searchModelHistorico, 'data_inicio')->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => ['id' => 'data_inicio-controle_interno'],
                                'widgetOptions' => [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'orientation' => 'top auto',
                                    ]
                                ]
                            ])->label('Data de início') ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($searchModelHistorico, 'data_fim')->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => ['id' => 'data_fim-controle_interno'],
                                'widgetOptions' => [
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'orientation' => 'top auto',
                                    ]
                                ]
                            ])->label('Data de término') ?>
                        </div>
                    </div>

                    <?= $form->field($searchModelHistorico, 'acaoIds')->widget(Select2::className(), [
                        'data' => $optionsAcao,
                        'theme' => Select2::THEME_KRAJEE,
                        'showToggleAll' => false,
                        'options' => ['id' => 'acaoIdsControleInterno', 'multiple' => true],
                        'pluginOptions' => ['allowClear' => true]
                    ])->label('Ações') ?>

                    <?= Html::submitButton(
                        Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                        ['class' => 'btn btn-danger']
                    ) ?>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
