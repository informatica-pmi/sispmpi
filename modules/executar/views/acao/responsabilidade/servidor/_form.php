<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use app\base\Txt;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
?>

<div class="servidor-form">
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 10,
        'min' => 0,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsServidor[0],
        'formId' => 'form-responsabilidade',
        'formFields' => [
            'nome',
            'masp_matricula',
            'unidade_administrativa_id',
        ],
    ]); ?>

    <h6 class="font-weight-semi-bold">Servidores envolvidos na ação</h6>
    <div class="card">
        <div class="card-body">
            <div class="container-items">
                <?php foreach ($modelsServidor as $index => $modelServidor) : ?>
                    <div class="item">
                        <?php
                        if (!$modelServidor->isNewRecord) {
                            echo Html::activeHiddenInput($modelServidor, "[{$index}]id");
                        }
                        ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($modelServidor, "[{$index}]nome")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($modelServidor, "[{$index}]masp_matricula")
                                    ->textInput(['maxlength' => true])
                                ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($modelServidor, "[{$index}]unidade_administrativa_id")
                                    ->widget(
                                        Select2::className(),
                                        [
                                            'data' => $optionsUnidadeAdministrativa,
                                            'theme' => Select2::THEME_KRAJEE_BS5,
                                            'options' => ['placeholder' => Txt::$t['prompt']],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]
                                    ) ?>
                            </div>
                        </div>
                        <?= Html::button(
                            Universal::icon('fas fa-times') . ' Remover',
                            [
                                'class' => 'remove-item btn btn-link text-danger p-0'
                            ]
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                [
                    'class' => 'add-item btn btn-link text-success p-0'
                ]
            ) ?>
        </div>
    </div>

    <?php DynamicFormWidget::end(); ?>
</div>
