<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $modelAcao app\models\Acao */
/* @var $modelsAcaoAvaliacaoRecomendacao app\models\AcaoAvaliacaoRecomendacao[] */
?>
<div class="avaliacao-form">
    <?php $form = ActiveForm::begin([
        'id' => 'avaliacao-form',
        'action' => ['@executar/acao/avaliacao/update', 'acaoId' => $modelAcao->id],
    ]) ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper_avaliacao',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 50,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsAcaoAvaliacaoRecomendacao[0],
        'formId' => 'avaliacao-form',
        'formFields' => [
            'resposta'
        ]
    ]) ?>

    <div class="card">
        <div class="card-body container-items">
            <?php foreach ($modelsAcaoAvaliacaoRecomendacao as $index => $modelAcaoAvaliacaoRecomendacao) : ?>
                <div class="item">
                    <?php
                    if (!$modelAcaoAvaliacaoRecomendacao->isNewRecord) :
                        echo Html::activeHiddenInput($modelAcaoAvaliacaoRecomendacao, "[{$index}]id");
                    endif;
                    ?>

                    <fieldset disabled>
                        <?= $form->field($modelAcaoAvaliacaoRecomendacao, "[{$index}]recomendacao")
                            ->textarea()
                            ->label(sprintf(
                                "%sº %s",
                                $index + 1,
                                $modelAcaoAvaliacaoRecomendacao->getAttributeLabel('recomendacao')
                            ))
                        ?>
                    </fieldset>

                    <fieldset>
                        <?= $form->field($modelAcaoAvaliacaoRecomendacao, "[{$index}]resposta")
                            ->textarea()
                            ->label('Resposta')
                        ?>
                    </fieldset>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php DynamicFormWidget::end(); ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', [
            'class' => 'btn btn-success',
            'data-confirm' => 'Prezado(a), ao enviar as respostas, a mesma não poderá ser alterada futuramente, ' .
                'deseja continuar?'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<<JS
    function verifyExistAnswerAvaliacao(index) {
        let formGroupResposta = $('.field-acaoavaliacaorecomendacao-' + index + '-resposta');

        let resposta = $(formGroupResposta).find('textarea').val();

        if (resposta) {
            $(formGroupResposta).closest('fieldset').attr('disabled', 'disabled');
        }
    }

    $('.dynamicform_wrapper_avaliacao .item').each(function(index) {
        verifyExistAnswerAvaliacao(index);
    });
JS;

$this->registerJs($js);
