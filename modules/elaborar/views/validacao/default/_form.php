<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\widgets\TinyMCE;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelValidacao app\models\Validacao */
/* @var $optionsStakeholder app\models\Stakeholder[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="validacao-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-validacao'
    ]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelValidacao, 'data_inicio')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelValidacao, 'data_conclusao')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
    </div>

    <div class="multiselect-container mb-3">
        <?php Pjax::begin(['id' => 'container-stakeholders']) ?>
            <?= $form->field($modelValidacao, 'stakeholderIds')->multiselect($optionsStakeholder) ?>
        <?php Pjax::end() ?>

        <div class="multiselect-footer">
            <?= Html::button('Outros', [
                'class' => 'btn btn-link p-0',
                'value' => Url::to(['@elaborar/validacao/outro-stakeholder/create']),
                'id' => 'modalOutrosStakeholders',
                'data-show' => 'modal-outro-stakeholder'
            ]) ?>
        </div>
    </div>

    <?= $form->field($modelValidacao, 'info_complementar')->widget(TinyMce::className()) ?>

    <div class="form-group d-inline-block">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

Universal::modal('Outros', 'modal-outro-stakeholder', 'modal-xl');

$js = <<<JS
    var stakeholdersStorage = [];
    localStorage.removeItem('@StakeholdersIds');

    $("input[id*='stakeholderids']").change(function() {
        var value = $(this).val();
        if($(this).prop('checked')) {
            stakeholdersStorage.push(value);
            localStorage.setItem('@StakeholdersIds', JSON.stringify(stakeholdersStorage));
        } else {
            stakeholdersStorage.splice(stakeholdersStorage.indexOf(value), 1);
            localStorage.setItem('@StakeholdersIds', JSON.stringify(stakeholdersStorage));
        }
    });
JS;

$this->registerJs($js);
