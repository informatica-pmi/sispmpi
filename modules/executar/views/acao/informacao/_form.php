<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\money\MaskMoney;
use app\base\Template;
use app\components\helpers\Universal;
use app\models\Acao;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $optionsTipo app\models\Tipo[] */
?>

<div class="informacao-form">
    <?php $form = ActiveForm::begin([
        'action' => ['@executar/acao/informacao/update', 'acaoId' => $modelAcao->id]
    ]); ?>

    <?= $form->field($modelAcao, 'executarAccordion')->hiddenInput([
        'value' => Acao::EXECUTAR_INFORMACOES
    ])->label(false) ?>

    <?= $form->field($modelAcao, 'classificacao', ['template' => Template::$t['radio']])
        ->radioButtonGroup(Acao::getClassificacao());
    ?>

    <div class="multiselect-container mb-3">
        <?php Pjax::begin(['id' => 'container-tipos']) ?>
            <?= $form->field($modelAcao, 'tipoIds')->multiselect($optionsTipo) ?>
        <?php Pjax::end() ?>

        <div class="multiselect-footer">
            <?= Html::button('Outros', [
                'class' => 'btn btn-link p-0',
                'value' => Url::to(['acao/outro-tipo/create']),
                'id' => 'modalOutrosTipos',
                'data-show' => 'modal-outro-tipo',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelAcao, 'previsao_inicio')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelAcao, 'previsao_conclusao')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelAcao, 'orcamento_previsto')->widget(MaskMoney::className()); ?>
        </div>
    </div>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

Universal::modal('Outros', 'modal-outro-tipo', 'modal-xl');

$js = <<<JS
    var tiposStorage = [];
    localStorage.removeItem('@TiposIds');

    $("input[id*='tipoids']").change(function() {
        var value = $(this).val();
        if($(this).prop('checked')) {
            tiposStorage.push(value);
            localStorage.setItem('@TiposIds', JSON.stringify(tiposStorage));
        } else {
            tiposStorage.splice(tiposStorage.indexOf(value), 1);
            localStorage.setItem('@TiposIds', JSON.stringify(tiposStorage));
        }
    });

    function disabledPrevisaoConclusaoField(value) {
        if (value === 'Ação pontual') {
            $('input[id*=\"acao-previsao_conclusao\"]').attr('disabled', false);
        } else {
            $('input[id*=\"acao-previsao_conclusao\"]').attr('disabled', true);
        }
    }

    disabledPrevisaoConclusaoField($('input[name=\"Acao[classificacao]\"]:checked').val());

    $('input[name=\"Acao[classificacao]\"]').change(function() {
        disabledPrevisaoConclusaoField($(this).val());
    });
JS;

$this->registerJs($js);
