<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\base\Txt;
use app\models\Status;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orgao */
/* @var $modelsContabil app\modules\admin\models\OrgaoContabil[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="orgao-form">

    <?php $form = ActiveForm::begin([
        'id' => 'orgao-form-dynamic'
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'sigla')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'tipo')->dropDownList(Orgao::getTipo(), ['prompt' => Txt::$t['prompt']]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Status::getPadrao(), ['prompt' => Txt::$t['prompt']]) ?>
        </div>
    </div>

    <?= $this->render('./contabil/_form', [
        'form' => $form,
        'models' => $modelsContabil
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
