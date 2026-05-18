<?php

use app\base\Txt;
use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;
use app\components\helpers\Universal;
use app\models\Historico;
use kartik\select2\Select2;
use yii\bootstrap4\ButtonDropdown;

/* @var $this yii\web\View */
$this->title = 'Gerar relatórios';
$this->params['breadcrumbs'][] = [
    'label' => 'Monitoramento das ações do programa de integridade',
    'url' => ['@monitorar']
];
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
                        'action' => ['@monitorar/relatorio/monitoramento/index'],
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

        <div class="card border-bottom-0">
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
                        'action' => ['@monitorar/relatorio/historico/index'],
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
                                        'orientation' => 'bottom auto',
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
                                        'orientation' => 'bottom auto',
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
    </div>
</div>
