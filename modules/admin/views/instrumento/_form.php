<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\base\Txt;
use app\components\helpers\Universal;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $model app\models\Instrumento */
/* @var $form kartik\form\ActiveForm */
?>

<div class="instrumento-form">

    <?php $form = ActiveForm::begin([
        'formConfig' => ['showErrors' => false]
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <div class="d-flex flex-row align-items-end justify-content-between">
        <div class="field" style="flex: 1">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="field ml-2" style="flex: 1">
            <?= $form->field($model, 'orgao_id')->dropDownList(
                Universal::getDropDown(Orgao::className(), true),
                ['prompt' => Txt::$t['prompt']]
            ) ?>
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
