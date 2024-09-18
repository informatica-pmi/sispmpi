<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Status;
use yii\grid\GridView;
use kartik\form\ActiveForm;
use app\modules\admin\models\Orgao;
use app\components\helpers\Universal;

?>

<div>
    <h6 class="my-4">Demais informações</h6>
    <div class="bg-white border card-result p-4 table-responsive">
        <?php Pjax::begin(['id' => 'form']) ?>
            <?php $form = ActiveForm::begin([
                'options' => [
                    'data-pjax' => true,
                    'class' => 'd-flex flex-column flex-md-row align-items-center justify-content-between mb-3 pb-3 border-bottom'
                ],
                'action' => ['index'],
                'method' => 'get',
                'fieldConfig' => ['options' => ['class' => 'form-group mb-0 mr-0 mr-md-2']]
            ]) ?>

            <span class="small mr-2 font-weight-semibold mb-3 mb-md-0">
                <?= Universal::icon('fas fa-filter') ?>
                Filtros
            </span>

            <div class="d-flex flex-column flex-md-row">
                <?= $form->field($searchModel, 'orgao_nome')->input(
                    'search',
                    ['class' => 'form-control form-control-sm', 'placeholder' => 'Órgão']
                )->label(false) ?>

                <?= $form->field($searchModel, 'orgao_tipo')->dropDownList(
                    Orgao::getTipo(),
                    ['class' => 'form-control form-control-sm', 'prompt' => 'Selecione o tipo']
                )->label(false) ?>

                <?= $form->field($searchModel, 'plano_integridade_status')->dropDownList(
                    Status::getPlanoStatus(),
                    ['class' => 'form-control form-control-sm', 'prompt' => 'Selecione o status']
                )->label(false) ?>

                <div class="form-group mb-0">
                    <?= Html::submitButton(Universal::icon('fas fa-search'), ['class' => 'btn btn-sm btn-light border w-100']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        <?php Pjax::end() ?>

        <?php Pjax::begin(['id' => 'gridview']) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'grid-view table-responsive'],
                'tableOptions' => ['class' => 'table table-hover table-sm small text-center'],
                'summaryOptions' => ['class' => 'small mb-2'],
                'headerRowOptions' => ['class' => 'thead-light'],
                'pager' => [
                    'class' => 'yii\bootstrap4\LinkPager',
                    'listOptions' => ['class' => 'pagination pagination-sm']
                ],
                'columns' => [
                    [
                        'attribute' => 'orgao_nome',
                        'label' => 'Órgão',
                        'filterInputOptions' => ['class' => 'form-control form-control-sm'],
                        'headerOptions' => ['class' => 'text-left'],
                        'contentOptions' => ['class' => 'text-left']
                    ],
                    [
                        'attribute' => 'orgao_tipo',
                        'label' => 'Tipo',
                        'value' => function ($model, $key, $index) {
                            return Orgao::getTipo($model['orgao_tipo']);
                        },
                        'filter' => Orgao::getTipo(),
                        'filterInputOptions' => ['class' => 'form-control form-control-sm']
                    ],
                    [
                        'attribute' => 'plano_integridade_status',
                        'label' => 'Status',
                        'value' => function ($model, $key, $index) {
                            return Status::getPlanoStatus($model['plano_integridade_status'] ?? Status::PLANO_N_INICIADO);
                        }
                    ],
                    [
                        'attribute' => 'plano_integridade_versao',
                        'format' => 'html',
                        'label' => 'Versão',
                        'value' => function ($model, $key, $index) {
                            return Html::tag(
                                'span',
                                number_format($model['plano_integridade_versao'], 2, '.', ''),
                                ['class' => 'text-success font-weight-semibold rounded-pill px-2 py-1 bg-green-chart-light']
                            );
                        }
                    ],
                    'eixos',
                    [
                        'attribute' => 'acoes',
                        'label' => 'Ações'
                    ],
                    [
                        'attribute' => 'acoes_atrasadas',
                        'label' => 'Ações Atrasadas',
                        'format' => 'html',
                        'enableSorting' => false,
                        'value' => function ($model, $key, $index) {
                            return Html::tag(
                                'span',
                                $model['acoes_atrasadas'],
                                ['class' => 'text-danger font-weight-semibold rounded-pill px-2 py-1 bg-red-chart-light']
                            );
                        }
                    ]
                ]
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
