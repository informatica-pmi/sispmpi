<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao[] */
?>

<div class="monitoramento-form">
    <?php $form = ActiveForm::begin([
        'id' => 'monitoramento-form',
        'action' => ['@executar/acao/monitoramento/update', 'acaoId' => $modelAcao->id]
    ]); ?>

    <h6 class="font-weight-semi-bold">Recomendações</h6>

    <?php if ($modelsAcaoMonitoramentoRecomendacao[0]->isNewRecord) : ?>
        <div class="card">
            <div class="card-body">
                <span class="text-muted">Nenhuma recomendação cadastrada.</span>
            </div>
        </div>
    <?php else : ?>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper_monitoramento',
            'widgetBody' => '.container-items',
            'widgetItem' => '.item',
            'limit' => 50,
            'min' => 0,
            'insertButton' => '.add-item',
            'deleteButton' => '.remove-item',
            'model' => $modelsAcaoMonitoramentoRecomendacao[0],
            'formId' => 'monitoramento-form',
            'formFields' => [
                'recomendacao'
            ],
        ]); ?>

        <div class="card">
            <div class="card-body pb-1">
                <div class="container-items">
                    <?php
                    foreach ($modelsAcaoMonitoramentoRecomendacao as $index => $modelAcaoMonitoramentoRecomendacao) :
                        ?>
                        <div class="item">
                            <?php
                            if (!$modelAcaoMonitoramentoRecomendacao->isNewRecord) :
                                echo Html::activeHiddenInput($modelAcaoMonitoramentoRecomendacao, "[{$index}]id");
                            endif;
                            ?>

                            <fieldset disabled>
                                <?= $form->field($modelAcaoMonitoramentoRecomendacao, "[{$index}]recomendacao")
                                    ->textarea()
                                    ->label(sprintf(
                                        "%sº %s",
                                        $index + 1,
                                        $modelAcaoMonitoramentoRecomendacao->getAttributeLabel('recomendacao')
                                    ))
                                ?>
                            </fieldset>

                            <fieldset>
                                <?= $form->field($modelAcaoMonitoramentoRecomendacao, "[{$index}]resposta")
                                    ->textarea()
                                    ->label('Resposta')
                                ?>
                            </fieldset>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php DynamicFormWidget::end(); ?>
    <?php endif; ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', [
            'class' => 'btn btn-success',
            'data-confirm' => 'Prezado(a), ao enviar as respostas, a mesma não poderá ser alterada futuramente,' .
                'deseja continuar?'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<<JS
    function verifyExistAnswerMonitoramento(index) {
        let formGroupResposta = $('.field-acaomonitoramentorecomendacao-' + index + '-resposta');

        let resposta = $(formGroupResposta).find('textarea').val();

        if (resposta) {
            $(formGroupResposta).closest('fieldset').attr('disabled', 'disabled');
        }
    }

    $('.dynamicform_wrapper_monitoramento .item').each(function(index) {
        verifyExistAnswerMonitoramento(index);
    });
JS;

$this->registerJs($js);
