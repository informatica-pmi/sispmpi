<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\models\Status;
use app\models\User;
use app\base\Txt;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form kartik\form\ActiveForm */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $optionsOrgao app\models\Orgao[] */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php if (User::getPerfil() === User::PERFIL_ADMINISTRADOR) : ?>
        <?= $form->field($model, 'orgao_id')->widget(Select2::className(), [
            'data' => $optionsOrgao,
            'theme' => Select2::THEME_KRAJEE_BS5,
            'options' => ['placeholder' => Txt::$t['prompt']],
            'pluginOptions' => ['allowClear' => true]
        ]) ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-sm-6">
            <?php
            echo $form->field($model, 'login', [
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'placement' => 'right',
                    'onIconHover' => true,
                    'onLabelClick' => true,
                    'onLabelHover' => true
                ]])
                ->textInput(['maxlength' => true])
                ->hint('O login deve ser formado pelo primeiro e ultimo nome do campo <strong>NOME</strong>.');
            ?>
        </div>
        <div class="perfil-field col-sm-6">
            <?= $form->field($model, 'perfil')
                ->dropDownList(User::getFilterAuditor(), ['prompt' => Txt::$t['prompt']])
            ?>
        </div>
        <div class="unidade-administrativa-field d-none col-sm-3">
            <?= $form->field($model, 'unidade_administrativa_id')->widget(Select2::className(), [
                'data' => $optionsUnidadeAdministrativa,
                'theme' => Select2::THEME_KRAJEE_BS5,
                'options' => ['placeholder' => Txt::$t['prompt']],
                'pluginOptions' => ['allowClear' => true]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'masp')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'telefone')->widget(MaskedInput::className(), [
                'mask' => '(99) 9999-9999'
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'status')->dropDownList(Status::getPadrao()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

// Executor = User::PERFIL_EXECUTOR

$js = <<<JS
    function visibleUnidadeAdministrativaField(value) {
        const showDivWithUnidadeAdministrativaField = $('.unidade-administrativa-field');
        const divWithPerfilField = $('.perfil-field');

        if (value === 'Executor') {
            divWithPerfilField.removeClass('col-sm-6').addClass('col-sm-3');
            showDivWithUnidadeAdministrativaField.removeClass('d-none');
        } else {
            divWithPerfilField.removeClass('col-sm-3').addClass('col-sm-6');
            showDivWithUnidadeAdministrativaField.addClass('d-none');
        }
    }

    visibleUnidadeAdministrativaField($('#user-perfil').val());

    $('#user-perfil').change(function() {
        visibleUnidadeAdministrativaField($(this).val());
    });
JS;
$this->registerJs($js);
