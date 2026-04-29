<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\InformacaoEstado */
/* @var $form kartik\form\ActiveForm */
?>

<div class="informacao-estado-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'ano')->widget(MaskedInput::className(), [
                'mask' => '9999'
            ]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'orcamento')->widget(MaskMoney::className()) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'quantitativo_pessoal')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
