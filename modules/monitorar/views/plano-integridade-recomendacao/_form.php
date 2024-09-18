<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii/web/View */
/* @var $modelsPlanoIntegridadeRecomendacao app/models/PlanoIntegridadeRecomendacao */
?>

<div class="plano-integridade-form">
    <?php $form = ActiveForm::begin(['id' => 'plano-integridade-form']) ?>

    <?php if ($modelsPlanoIntegridadeRecomendacao[0]->isNewRecord) : ?>
        <div class="card">
            <div class="card-body">
                <span class="text-muted">Nenhuma recomendação cadastrada.</span>
            </div>
        </div>
    <?php else : ?>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper',
            'widgetBody' => '.container-items',
            'widgetItem' => '.item',
            'limit' => 99,
            'min' => 1,
            'insertButton' => '.add-item',
            'deleteButton' => '.remove-item',
            'model' => $modelsPlanoIntegridadeRecomendacao[0],
            'formId' => 'plano-integridade-form',
            'formFields' => [
                'recomendacao',
                'resposta',
            ],
        ]); ?>

        <div class="container-items">
            <?php
            foreach ($modelsPlanoIntegridadeRecomendacao as $index => $modelPlanoIntegridadeRecomendacao) :
                ?>
                <div class="item">
                    <?php
                    if (!$modelPlanoIntegridadeRecomendacao->isNewRecord) :
                        echo Html::activeHiddenInput($modelPlanoIntegridadeRecomendacao, "[{$index}]id");
                    endif;
                    ?>

                    <fieldset disabled>
                        <?= $form->field($modelPlanoIntegridadeRecomendacao, "[{$index}]recomendacao")
                            ->textarea()
                        ?>
                    </fieldset>

                    <fieldset <?= $modelPlanoIntegridadeRecomendacao->resposta ? 'disabled' : '' ?> >
                        <?= $form->field($modelPlanoIntegridadeRecomendacao, "[{$index}]resposta")
                            ->textarea()
                        ?>
                    </fieldset>
                </div>
            <?php endforeach; ?>
        </div>

        <?php DynamicFormWidget::end(); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', [
            'class' => 'btn btn-success',
            'data-confirm' => 'Prezado(a), ao enviar as respostas, a mesma não poderá ser alterada futuramente, ' .
                'deseja continuar?'
        ]) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
