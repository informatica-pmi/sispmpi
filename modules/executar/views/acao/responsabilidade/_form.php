<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\base\Txt;
use app\models\Acao;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $modelServidor app\models\Servidor */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
?>

<div class="responsabilidade-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-responsabilidade',
        'action' => ['@executar/acao/responsabilidade/update', 'acaoId' => $modelAcao->id]
    ]); ?>

    <?= Html::activeHiddenInput($modelAcao, 'executarAccordion', ['value' => Acao::EXECUTAR_RESPONSABILIDADE]) ?>

    <?= $form->field($modelAcao, 'unidade_executora')->widget(Select2::className(), [
        'data' => $optionsUnidadeAdministrativa,
        'theme' => Select2::THEME_KRAJEE_BS5,
        'options' => ['placeholder' => Txt::$t['prompt']],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <div class="justificativa-unidade-executora d-none">
        <?= $form->field($modelAcao, 'justificativa_unidade_executora')->textarea() ?>
    </div>

    <?= $form->field($modelAcao, 'unidadeApoioIds')->multiselect($optionsUnidadeAdministrativa, [
        'container' => ['class' => 'multiselect']
    ]) ?>

    <h6 class="font-weight-semi-bold">Servidor ou servidora responsável pela ação</h6>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($modelServidor, 'nome')->textInput() ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelServidor, 'masp_matricula')->textInput() ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelServidor, 'unidade_administrativa_id')
                        ->widget(Select2::className(), [
                            'data' => $optionsUnidadeAdministrativa,
                            'theme' => Select2::THEME_KRAJEE_BS5,
                            'options' => ['placeholder' => Txt::$t['prompt']],
                            'pluginOptions' => ['allowClear' => true],
                        ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->render('./servidor/_form', [
        'form' => $form,
        'modelsServidor' => $modelsServidor,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
    ]) ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
    const unidadeExecutoraElement = $('#acao-unidade_executora');
    const initialValueUnidadeExecutora = unidadeExecutoraElement.val();
    const justificativaUnidadeExecutoraElement = $(".justificativa-unidade-executora");

    unidadeExecutoraElement.change(function() {
        const unidadeValue = $(this).val();
        if (unidadeValue.length > 0 && unidadeValue != initialValueUnidadeExecutora) {
            justificativaUnidadeExecutoraElement.removeClass('d-none');
        } else {
            justificativaUnidadeExecutoraElement.addClass('d-none');
        }
    });
JS;
$this->registerJs($js);
