<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\base\Template;
use app\components\helpers\Universal;
use app\modules\monitorar\models\AcaoMonitoramento;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="monitoramento-form">

    <?php $form = ActiveForm::begin([
        'id' => 'monitoramento-form',
        'action' => ['@monitorar/acao/monitoramento/create', 'acaoId' => $modelAcao->id],
    ]); ?>

    <?= $form->field($modelAcaoMonitoramento, 'risco_n_implementacao', ['template' => Template::$t['radio']])
        ->radioButtonGroup(AcaoMonitoramento::getRisco())
    ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
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

    <h6 class="font-weight-semi-bold">Recomendações</h6>
    <div class="card">
        <div class="card-body">
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

                        <fieldset>
                            <?= $form->field($modelAcaoMonitoramentoRecomendacao, "[{$index}]recomendacao")
                                ->textarea()
                                ->label($index + 1 . "º recomendação")
                            ?>

                            <div class="d-none">
                                <?= $form->field($modelAcaoMonitoramentoRecomendacao, "[{$index}]resposta")
                                    ->textarea()
                                ?>
                            </div>

                            <?= Html::button(
                                Universal::icon('fas fa-times') . ' Remover',
                                ['class' => 'remove-item btn btn-link text-danger p-0']
                            ) ?>
                        </fieldset>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                ['class' => 'add-item btn btn-link text-success p-0']
            ) ?>
        </div>
    </div>

    <?php DynamicFormWidget::end(); ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
    function verifyExistAnswer(index) {
        let formGroupResposta = $('.field-acaomonitoramentorecomendacao-' + index + '-resposta');

        let resposta = $(formGroupResposta).find('textarea').val();

        if (resposta) {
            $(formGroupResposta).closest('div.d-none').removeClass('d-none');

            $(formGroupResposta).closest('fieldset').attr('disabled', 'disabled');
        }
    }

    $('.dynamicform_wrapper .item').each(function(index) {
        verifyExistAnswer(index);
    });

    $('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        $('.dynamicform_wrapper .item').each(function(index) {
            $(this).find('label[for*="-recomendacao"]').html((index + 1) + 'º recomendação');
        });
    });

    $('.dynamicform_wrapper').on('afterDelete', function(e) {
        $('.dynamicform_wrapper .item').each(function(index) {
            $(this).find('label[for*="-recomendacao"]').html((index + 1) + 'º recomendação');
        });
    });
JS;

$this->registerJs($js);
