<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form kartik\form\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?php
            echo $form->field($model, 'login', [
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'placement' => 'right',
                    'onIconHover' => true,
                    'onLabelClick' => true,
                    'onLabelHover' => true
                ]])
                ->textInput(['maxlength' => true])
                ->hint('O login deve ser formado pelo primeiro e ultimo nome do campo <strong>NOME</strong>.');
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Status::getPadrao()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'masp')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'telefone')->widget(MaskedInput::className(), [
                'mask' => '(99) 9999-9999'
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
