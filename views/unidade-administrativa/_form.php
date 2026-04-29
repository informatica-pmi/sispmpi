<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadeAdministrativa */
/* @var $form kartik\form\ActiveForm */
?>

<div class="unidade-administrativa-form">

    <?php $form = ActiveForm::begin([
        'formConfig' => ['showErrors' => false]
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <div class="d-flex flex-row align-items-end justify-content-between">
        <div class="field" style="flex: 1">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="form-group ml-2">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

            <?php if (!$model->isNewRecord) : ?>
                <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
