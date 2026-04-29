<?php

use yii\helpers\Html;
use app\components\widgets\TinyMCE;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelReuniao app\modules\monitorar\models\Reuniao */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="reuniao-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-reuniao'
    ]); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($modelReuniao, 'data')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-sm-8">
            <?= $form->field($modelReuniao, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $this->render('./servidor/_form', [
        'form' => $form,
        'modelsServidor' => $modelsServidor,
    ]) ?>

    <?= $form->field($modelReuniao, 'pauta')->widget(TinyMce::className()) ?>

    <?= $form->field($modelReuniao, 'registro')->widget(TinyMce::className()) ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', [
            'class' => 'btn btn-success',
            'data-confirm' => 'Deseja realmente salvar o registro da reunião? Caso confirme a ação, ' .
                'o registro não poderá ser deletado posteriormente.'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
