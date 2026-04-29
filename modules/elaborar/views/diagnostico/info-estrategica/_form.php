<?php

use app\components\widgets\TinyMCE;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\form\ActiveField;

/* @var $this yii\web\View */
/* @var $modelDiagnosticoInfoEstrategica app\modules\elaborar\models\DiagnosticoInfoEstrategica */
/* @var $form kartik\form\ActiveForm */
/* @var $diagnosticoId */
?>

<div class="diagnostico-info-estrategica-form">

    <?php $form = ActiveForm::begin([
        'action' => ['@elaborar/diagnostico/info-estrategica/create', 'diagnosticoId' => $diagnosticoId]
    ]); ?>

    <?= $form->field($modelDiagnosticoInfoEstrategica, 'missao', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMCE::className())->hint('Descrever a missão, ou seja, o propósito do órgão/entidade.') ?>


    <?= $form->field($modelDiagnosticoInfoEstrategica, 'visao', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMCE::className())->hint('Descrever a visão, situação em que o órgão/entidade deseja chegar (em período definido).') ?>

    <?= $form->field($modelDiagnosticoInfoEstrategica, 'valores', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMce::className())->hint('Descrever os valores, ideais de atitude, comportamento e resultados do órgão/entidade.') ?>

    <?= $form->field($modelDiagnosticoInfoEstrategica, 'estrutura_organica', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMce::className())->hint('Descrever a estrutura orgânica do órgão/entidade, conforme decreto de competências ou organograma.'); ?>

    <?= $form->field($modelDiagnosticoInfoEstrategica, 'competencias', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMce::className())->hint('Descrever as principais competências do órgão/entidade, conforme decreto.'); ?>

    <?= $form->field($modelDiagnosticoInfoEstrategica, 'atribuicoes', [
        'hintType' => ActiveField::HINT_SPECIAL,
        'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
    ])->widget(TinyMce::className())->hint('Descrever as principais atribuições do órgão/entidade, conforme decreto de competências.'); ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar e Próximo', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
