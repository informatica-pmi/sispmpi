<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $modelsServidor app\models\Servidor[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="servidor-form">
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 999,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsServidor[0],
        'formId' => 'form-reuniao',
        'formFields' => [
            'nome',
        ],
    ]); ?>

    <h6 class="font-weight-semi-bold">Participantes da reunião</h6>
    <div class="card">
        <div class="card-body">
            <div class="container-items">
                <?php foreach ($modelsServidor as $index => $modelServidor) : ?>
                    <div class="item">
                        <?php
                        if (!$modelServidor->isNewRecord) :
                            echo Html::activeHiddenInput($modelServidor, "[{$index}]id");
                        endif;
                        ?>

                        <?= $form->field($modelServidor, "[{$index}]nome")->textInput(['maxlength' => true]) ?>

                        <?= Html::button(
                            Universal::icon('fas fa-times') . ' Remover',
                            ['class' => 'remove-item btn btn-link text-danger p-0']
                        ) ?>
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
</div>
