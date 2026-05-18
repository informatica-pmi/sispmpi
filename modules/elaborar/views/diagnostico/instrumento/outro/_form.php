<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsInstrumento app\models\Instrumento[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="outro-instrumento-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-outro-instrumento'
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 4,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsInstrumento[0],
        'formId' => 'form-outro-instrumento',
        'formFields' => [
           'nome',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="card-group container-items">
                <?php foreach ($modelsInstrumento as $index => $modelInstrumento) : ?>
                    <div class="item card">
                        <div class="card-body">
                            <?php
                            if (!$modelInstrumento->isNewRecord) :
                                echo Html::activeHiddenInput($modelInstrumento, "[{$index}]id");
                            endif;
                            ?>

                            <?= $form->field($modelInstrumento, "[{$index}]nome")->textInput(['maxlength' => true]) ?>

                            <?= Html::button(
                                Universal::icon('fas fa-times') . ' Remover',
                                ['class' => 'remove-item btn btn-link text-danger p-0']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                ['class' => 'add-item btn btn-link text-success p-0']
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
    $('#form-outro-instrumento').on('beforeSubmit', function(e) {
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
                    var instrumentosStorage = JSON.parse(localStorage.getItem('@InstrumentosIds'));

                    instrumentosStorage = instrumentosStorage === null ? [] : instrumentosStorage;

                    var instrumentosData = JSON.parse(data);
                    var newInstrumentosStorage = $.merge(instrumentosStorage, instrumentosData);

                    $('#modal-outro-instrumento').modal('hide');

                    $.pjax.reload({container: '#container-instrumentos'});
                    localStorage.setItem('@InstrumentosIds', JSON.stringify(newInstrumentosStorage));

                    setTimeout(function() {
                        $("input[id*='instrumentoids']").each(function() {
                            if ($.inArray($(this).val(), newInstrumentosStorage) > -1) {
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
