<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoAvaliacaoRecomendacaos app\models\AcaoAvaliacaoRecomendacao[] */
?>
<div class="avaliacao-form">
    <?php $form = ActiveForm::begin([
        'id' => 'avaliacao-form',
        'action' => ['@avaliar/acao/avaliacao/create', 'acaoId' => $modelAcao->id],
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 50,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelAcaoAvaliacaoRecomendacaos[0],
        'formId' => 'avaliacao-form',
        'formFields' => [
            'recomendacao',
            'resposta',
        ],
    ]); ?>

    <div class="card">
        <div class="card-body container-items">
            <?php foreach ($modelAcaoAvaliacaoRecomendacaos as $index => $modelAcaoAvaliacaoRecomendacao) : ?>
                <div class="item">
                    <?php
                    if (!$modelAcaoAvaliacaoRecomendacao->isNewRecord) :
                        echo Html::activeHiddenInput($modelAcaoAvaliacaoRecomendacao, "[{$index}]id");
                    endif;
                    ?>

                    <fieldset>
                        <?= $form->field($modelAcaoAvaliacaoRecomendacao, "[{$index}]recomendacao")
                            ->textarea()
                            ->label($index + 1 . "º recomendação")
                        ?>

                        <div class="d-none">
                            <?= $form->field($modelAcaoAvaliacaoRecomendacao, "[{$index}]resposta")
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
        let formGroupResposta = $('.field-acaoavaliacaorecomendacao-' + index + '-resposta');

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
