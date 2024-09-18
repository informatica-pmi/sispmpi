<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsStakeholderStakeholder app\models\Stakeholder[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="outro-stakeholder-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-outro-stakeholder'
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 4,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsStakeholder[0],
        'formId' => 'form-outro-stakeholder',
        'formFields' => [
            'nome',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="card-group container-items">
                <?php foreach ($modelsStakeholder as $index => $modelStakeholder) : ?>
                    <div class="item card">
                        <div class="card-body">

                            <?php
                            // necessary for update action.
                            if (!$modelStakeholder->isNewRecord) {
                                echo Html::activeHiddenInput($modelStakeholder, "[{$index}]id");
                            }
                            ?>

                            <?= $form->field($modelStakeholder, "[{$index}]nome")->textInput(['maxlength' => true]) ?>

                            <?= Html::button(
                                Universal::icon('fas fa-times') . ' Remover',
                                [
                                    'class' => 'remove-item btn btn-link text-danger p-0',
                                ]
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                [
                    'class' => 'add-item btn btn-link text-success p-0',
                ]
            ) ?>
        </div>
    </div>

    <?php DynamicFormWidget::end(); ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    $('#form-outro-stakeholder').on('beforeSubmit', function(e) {
        var formData = new FormData(this);
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(data) {
                if (data != 0) {
                    var stakholdersStorage = JSON.parse(localStorage.getItem('@StakeholdersIds'));

                    stakholdersStorage = stakholdersStorage === null ? [] : stakholdersStorage;

                    var stakeholdersData = JSON.parse(data);
                    var newStakeholdersStorage = $.merge(stakholdersStorage, stakeholdersData);

                    $('#modal-outro-stakeholder').modal('hide');

                    $.pjax.reload({container: '#container-stakeholders'});
                    localStorage.setItem('@StakeholdersIds', JSON.stringify(newStakeholdersStorage));

                    setTimeout(function() {
                        $("input[id*='stakeholderids']").each(function() {
                            if ($.inArray($(this).val(), newStakeholdersStorage) > -1) {
                                $(this).prop('checked', true);
                            }
                        });
                    }, 500);
                }
            }
        });

        return false;
    });
JS;
$this->registerJs($js);
