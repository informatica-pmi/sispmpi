<?php

use yii\helpers\Html;
use app\components\widgets\TinyMCE;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelEixo app\models\Eixo */
/* @var $form kartik\form\ActiveForm */
/* @var $planoId */
?>

<div class="eixo-form">

    <?php $form = ActiveForm::begin([
        'action' => $modelEixo->isNewRecord ?
            ['@elaborar/redacao/eixo/create', 'planoId' => $planoId] :
            ['@elaborar/redacao/eixo/update', 'eixoId' => $modelEixo->id],
    ]); ?>

    <?= $form->field($modelEixo, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelEixo, 'descricao')->widget(TinyMce::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
