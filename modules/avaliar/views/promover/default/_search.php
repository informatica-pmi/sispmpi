<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $model app\modules\avaliar\models\pesquisa\PromoverSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promover-integridade-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="d-flex flex-column flex-md-row">
        <div class="flex-grow-1 mr-1">
            <?= $form->field($model, 'data')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Selecione a data de início'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                        'clearBtn' => true,
                    ]
                ]
            ])->label(false) ?>
        </div>

        <div class="flex-grow-1">
            <?= $form->field($model, 'dataFim')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ['placeholder' => 'Selecione a data de término'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                        'clearBtn' => true,
                    ]
                ]
            ])->label(false) ?>
        </div>

        <div class="form-group ml-1 text-center">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>

            <?= Html::a(
                Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                ['@avaliar/promover/pdf/index', 'data' => $model->data, 'dataFim' => $model->dataFim],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer'
                ]
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
