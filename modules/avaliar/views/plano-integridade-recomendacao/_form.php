<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii/web/View */
/* @var $models app/models/PlanoIntegridadeRecomendacao */
?>

<div class="plano-integridade-form">
    <?php $form = ActiveForm::begin(['id' => 'plano-integridade-form']) ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 99,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $models[0],
        'formId' => 'plano-integridade-form',
        'insertPosition' => 'top',
        'formFields' => [
            'recomendacao',
            'resposta',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                ['class' => 'add-item btn btn-link text-success p-0']
            ) ?>
        </div>
        <div class="card-body">
            <div class="container-items">
                <?php foreach ($models as $index => $model) : ?>
                    <div class="item">
                        <?php
                        if (!$model->isNewRecord) :
                            echo Html::activeHiddenInput($model, "[{$index}]id");
                        endif;
                        ?>

                        <fieldset>
                            <?= $form->field($model, "[{$index}]recomendacao")
                                ->textarea()
                            ?>

                            <div class="d-none">
                                <?= $form->field($model, "[{$index}]resposta")
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
    </div>

    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>

<?php

$js = <<<JS
    function verifyExistAnswer(index) {
        let formGroupResposta = $('.field-planointegridaderecomendacao-' + index + '-resposta');

        let resposta = $(formGroupResposta).find('textarea').val();

        if (resposta) {
            $(formGroupResposta).closest('div.d-none').removeClass('d-none');

            $(formGroupResposta).closest('fieldset').attr('disabled', 'disabled');
        }
    }

    $('.dynamicform_wrapper .item').each(function(index) {
        verifyExistAnswer(index);
    });
JS;

$this->registerJs($js);
