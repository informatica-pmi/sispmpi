<?php

use app\base\Txt;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\admin\models\Monitoramento */
/* @var $optionsOrgao app\admin\models\Orgao[] */
/* @var $form kartik\form\ActiveFormm */
?>

<div class="monitoramento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'orgao_id')->widget(Select2::className(), [
        'data' => $optionsOrgao,
        'theme' => Select2::THEME_KRAJEE_BS5,
        'options' => ['placeholder' => Txt::$t['prompt']],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Alterar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
