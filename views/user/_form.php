<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\base\Txt;
use app\components\helpers\Universal;
use app\models\Status;
use app\models\User;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form kartik\form\ActiveForm */

$readOnly = !ArrayHelper::isIn(User::getPerfil(), [User::PERFIL_ADMINISTRADOR, User::PERFIL_TI]);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'readOnly' => $readOnly]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readOnly' => $readOnly]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'login')->textInput(['maxlength' => true, 'readOnly' => $readOnly]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Status::getPadrao(), ['readOnly' => $readOnly]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'senha')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'matricula')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'telefone')->widget(MaskedInput::className(), [
                'mask' => '(99) 9999-9999'
            ]) ?>
        </div>
    </div>

    <?php if (User::getPerfil() === User::PERFIL_ADMINISTRADOR && $model->perfil != User::PERFIL_ADMINISTRADOR) : ?>
        <?= $form->field($model, 'orgao_id')->widget(Select2::className(), [
            'data' => Universal::getDropDown(Orgao::className(), true),
            'theme' => Select2::THEME_KRAJEE_BS5,
            'options' => ['placeholder' => Txt::$t['prompt']],
            'pluginOptions' => ['allowClear' => true]
        ]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(
            !empty(User::getIdentidade('masp')) ? 'Salvar' : 'Finalizar Cadastro',
            ['class' => 'btn btn-success']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
