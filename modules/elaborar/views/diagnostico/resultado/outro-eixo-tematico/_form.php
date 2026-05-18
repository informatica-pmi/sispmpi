<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsEixoTematico app\models\EixoTematico[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="outro-eixo-tematico-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-outro-eixo-tematico'
    ]); ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 4,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsEixoTematico[0],
        'formId' => 'form-outro-eixo-tematico',
        'formFields' => [
           'nome',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="card-group container-items">
                <?php foreach ($modelsEixoTematico as $index => $modelEixoTematico) : ?>
                    <div class="item card">
                        <div class="card-body">
                            <?php
                            if (!$modelEixoTematico->isNewRecord) :
                                echo Html::activeHiddenInput($modelEixoTematico, "[{$index}]id");
                            endif;
                            ?>

                            <?= $form->field($modelEixoTematico, "[{$index}]nome")->textInput(['maxlength' => true]) ?>

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
    $('#form-outro-eixo-tematico').on('beforeSubmit', function(e) {
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
                    var eixosTematicosStorage = JSON.parse(localStorage.getItem('@EixosTematicosIds'));

                    eixosTematicosStorage = eixosTematicosStorage === null ? [] : eixosTematicosStorage;

                    var eixosTematicosData = JSON.parse(data);
                    var newEixosTematicosStorage = $.merge(eixosTematicosStorage, eixosTematicosData);

                    $('#modal-outro-eixo-tematico').modal('hide');

                    $.pjax.reload({container: '#container-eixos-tematicos'});
                    localStorage.setItem('@EixosTematicosIds', JSON.stringify(newEixosTematicosStorage));

                    setTimeout(function() {
                        $("input[id*='eixotematicoids']").each(function() {
                            if ($.inArray($(this).val(), newEixosTematicosStorage) > -1) {
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
