<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\monitorar\models\pesquisa\ReuniaoSearch */
/* @var $form kartik\form\ActiveForm */
?>

<div class="reuniao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="d-flex">
        <div class="d-flex flex-grow-1">
            <div class="flex-grow-1 mr-1">
                <?= $form->field($model, 'globalSearch')->textInput([
                    'placeholder' => 'Digite o nome da reunião, pauta e o registro para pesquisar'
                ])->label(false) ?>
            </div>

            <?= $form->field($model, 'data')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Selecione a data'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                        'clearBtn' => true,
                    ]
                ]
            ])->label(false) ?>
        </div>

        <div class="form-group ml-1">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
