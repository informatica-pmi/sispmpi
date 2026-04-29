<?php

use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelPromover app\modules\avaliar\models\Promover */
/* @var $form kartik\form\ActiveForm */
?>

<div class="promover-integridade-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-promover-integridade'
    ]); ?>

    <?= $form->field($modelPromover, 'acao_desenvolvida')->textarea(['rows' => 4]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($modelPromover, 'data')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modelPromover, 'horas_trabalho')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
