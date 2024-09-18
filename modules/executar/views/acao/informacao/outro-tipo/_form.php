<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsTipo app\models\Tipo[] */
?>

<div class="informacao-form">
    <?php $form = ActiveForm::begin([
        'id' => 'form-outro-tipo'
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 4,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsTipo[0],
        'formId' => 'form-outro-tipo',
        'formFields' => [
           'nome',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="card-group container-items">
                <?php foreach ($modelsTipo as $index => $modelTipo) : ?>
                    <div class="item card">
                        <div class="card-body">
                            <?= $form->field($modelTipo, "[{$index}]nome")->textInput(['maxlength' => true]) ?>

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
    $('#form-outro-tipo').on('beforeSubmit', function(e) {
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
                    var tiposStorage = JSON.parse(localStorage.getItem('@TiposIds'));

                    tiposStorage = tiposStorage === null ? [] : tiposStorage;

                    var tiposData = JSON.parse(data);
                    var newTiposStorage = $.merge(tiposStorage, tiposData);

                    $('#modal-outro-tipo').modal('hide');

                    $.pjax.reload({container: '#container-tipos'});
                    localStorage.setItem('@TiposIds', JSON.stringify(newTiposStorage));

                    setTimeout(function() {
                        $("input[id*='tipoids']").each(function() {
                            if ($.inArray($(this).val(), newTiposStorage) > -1) {
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
