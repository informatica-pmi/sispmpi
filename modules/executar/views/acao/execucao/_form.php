<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\components\widgets\TinyMCE;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\money\MaskMoney;
use kartik\form\ActiveField;
use app\base\Template;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
/* @var $acaoExecucaoEvidenciaFiles app\modules\executar\models\AcaoExecucaoArquivo[] */
/* @var $optionsFatorLimitante app\models\FatorLimitante[] */
/* @var $disabledFieldAcaoExecucaoEvidenciaFiles */
?>

<div class="execucao-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'action' => ['@executar/acao/execucao/create', 'acaoId' => $modelAcao->id]
    ]); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelAcaoExecucao, 'data_inicio')->widget(DateControl::className(), [
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
            <?= $form->field($modelAcaoExecucao, 'data_conclusao', [
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'onIconHover' => true,
                ],
            ])->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ],
                ],
            ])->hint('
                Serão consideradas como concluídas, todas as ações cuja data de início e data de conclusão
                estejam informadas. Serão consideradas como descontinuadas, todas as ações cuja data de início não
                estiver preenchida e data de descontinuação estiver informada.
            '); ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($modelAcaoExecucao, 'orcamento_executado')->widget(MaskMoney::className(), [
                'options' => ['placeholder' => 'R$ 0,00']
            ]); ?>
        </div>
    </div>

    <?= $form->field($modelAcaoExecucao, 'observacoes')->widget(TinyMce::className()) ?>

    <div class="multiselect-container mb-3">
        <?php Pjax::begin(['id' => 'container-fatores-limitantes']) ?>

        <?= $form->field($modelAcaoExecucao, 'fatoresLimitantesIds')->multiselect($optionsFatorLimitante) ?>

        <?php Pjax::end() ?>

        <div class="multiselect-footer">
            <?= Html::button('Outros', [
                'class' => 'btn btn-link p-0',
                'value' => Url::to(['acao/outro-fator-limitante/create']),
                'id' => 'modalOutrosFatoresLimitantes',
                'data-show' => 'modal-outro-fator-limitante',
            ]) ?>
        </div>
    </div>

    <?= $form->field($modelAcaoExecucao, 'evidencias_sugeridas')->widget(TinyMce::className()) ?>

    <h6 class="font-weight-semi-bold">Evidências que comprovam a execução da ação</h6>
    <div class="card">
        <div class="card-body">
            <?= $form->field($modelAcaoExecucao, 'evidencia_link')->textInput(); ?>

            <fieldset <?= $disabledFieldAcaoExecucaoEvidenciaFiles?> >
                <?= $form->field($modelAcaoExecucao, 'evidenciaFiles[]', [
                    'template' => Template::$t['file']
                ])->fileInput([
                      'multiple' => true
                ]) ?>
            </fieldset>

            <?php if (!empty($acaoExecucaoEvidenciaFiles)) : ?>
                <?php foreach ($acaoExecucaoEvidenciaFiles as $acaoExecucaoEvidenciaFile) : ?>
                    <div>
                        <?= Html::a(
                            $acaoExecucaoEvidenciaFile->arquivo->nome_original,
                            ['/arquivo/download', 'token' => $acaoExecucaoEvidenciaFile->arquivo->token]
                        ) ?>
                        <?= Html::a(
                            Universal::icon('fas fa-times-circle'),
                            [
                                '/arquivo/delete',
                                'id' => $acaoExecucaoEvidenciaFile->arquivo_id,
                                'execucaoArquivoId' => $acaoExecucaoEvidenciaFile->id
                            ],
                            [
                                'class' => 'text-danger',
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                ]
                            ]
                        ) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($modelAcaoExecucao, 'processo_sei')->textInput(); ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

Universal::modal('Outros', 'modal-outro-fator-limitante', 'modal-xl');

$js = <<<JS
    var tiposStorage = [];
    localStorage.removeItem('@FatoresLimitantesIds');

    $("input[id*='fatoreslimitantesids']").change(function() {
        var value = $(this).val();
        if($(this).prop('checked')) {
            tiposStorage.push(value);
            localStorage.setItem('@FatoresLimitantesIds', JSON.stringify(tiposStorage));
        } else {
            tiposStorage.splice(tiposStorage.indexOf(value), 1);
            localStorage.setItem('@FatoresLimitantesIds', JSON.stringify(tiposStorage));
        }
    });

    function disabledOrcamentoExecutadoField(size) {
        if (size > 0) {
            $('input[id*=\"acaoexecucao-orcamento_executado\"]').attr('disabled', false);
        } else {
            $('input[id*=\"acaoexecucao-orcamento_executado\"]').attr('disabled', true);
        }
    }

    disabledOrcamentoExecutadoField($('#acaoexecucao-data_inicio').val().length);

    $('#acaoexecucao-data_inicio').change(function() {
        disabledOrcamentoExecutadoField($(this).val().length);
    });
JS;

$this->registerJs($js);
