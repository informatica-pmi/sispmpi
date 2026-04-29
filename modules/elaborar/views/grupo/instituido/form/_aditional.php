<?php

use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelGrupoInstituido app\models\GrupoInstituido */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-instituido-form">

    <?php $form = ActiveForm::begin(['id' => 'grupo-instituido-aditional-form']) ?>

    <?= $form->field($modelGrupoInstituido, 'nome_numero')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($modelGrupoInstituido, 'data_publicacao')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => ['id' => 'grupoinstituido-data_publicacao-aditional'],
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelGrupoInstituido, 'dias_conclusao')->textInput() ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($modelGrupoInstituido, 'link')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
