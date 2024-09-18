<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;
use kartik\select2\Select2;
use app\base\Txt;
use app\components\helpers\Universal;
use app\models\Status;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form kartik\form\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
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
        <div class="col-sm-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'orgao_id')->widget(Select2::className(), [
                'data' => Universal::getDropDown(Orgao::className(), true),
                'theme' => Select2::THEME_KRAJEE_BS5,
                'options' => ['placeholder' => Txt::$t['prompt']],
                'pluginOptions' => ['allowClear' => true]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'status')->dropDownList(Status::getPadrao()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
