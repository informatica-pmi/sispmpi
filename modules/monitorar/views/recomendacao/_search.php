<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $model app\models\pesquisa\AcaoSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $isObservador */
?>

<div class="recomendacao-search">

    <?php $form = ActiveForm::begin([
        'action' => $isObservador ? ['@monitorar/recomendacao/index'] : ['index'],
        'method' => 'get',
    ]); ?>

    <div class="d-flex align-items-center">
        <div class="d-flex flex-grow-1">
            <div class="flex-grow-1 mr-1">
                <?= $form->field($model, 'globalSearch')->textInput([
                    'placeholder' => 'Digite para pesquisar'
                ])->label(false) ?>
            </div>

            <?= $form->field($model, 'status')->dropDownList(
                Status::getAcaoStatus(),
                ['prompt' => 'Selecione o status']
            )->label(false) ?>

            <?= Html::activeHiddenInput($model, 'filter', ['value' => $model->filter]) ?>
        </div>

        <div class="form-group ml-1">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
