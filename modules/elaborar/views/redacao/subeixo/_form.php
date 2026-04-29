<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\components\widgets\TinyMCE;
use app\base\Txt;

/* @var $this yii\web\View */
/* @var $modelSubeixo app\models\Subeixo */
/* @var $form kartik\form\ActiveForm */
/* @var $planoId */
/* @var $optionsEixo app\modules\elaborar\Eixo */
?>

<div class="subeixo-form">

    <?php $form = ActiveForm::begin([
        'action' => $modelSubeixo->isNewRecord ?
            ['@elaborar/redacao/subeixo/create', 'planoId' => $planoId] :
            ['@elaborar/redacao/subeixo/update', 'subeixoId' => $modelSubeixo->id]
    ]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelSubeixo, 'eixo_id')->dropDownList($optionsEixo, ['prompt' => Txt::$t['prompt']]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelSubeixo, 'titulo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($modelSubeixo, 'descricao')->widget(TinyMce::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
