<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use app\components\helpers\Universal;
use app\components\widgets\TinyMCE;

/* @var $this yii\web\View */
/* @var $modelDiagnosticoInstrumento app\modules\elaborar\models\DiagnosticoInstrumento */
/* @var $modelDiagnosticoResultado app\modules\elaborar\models\DiagnosticoResultado */
/* @var $optionsInstrumento app\models\Instrumento[] */
/* @var $form kartik\form\ActiveForm */
/* @var $diagnosticoId */
?>

<div class="diagnostico-instrumento-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-instrumento',
        'action' => ['@elaborar/diagnostico/instrumento/create', 'diagnosticoId' => $diagnosticoId],
    ]); ?>

    <div class="multiselect-container mb-3">
        <?php Pjax::begin(['id' => 'container-instrumentos']) ?>
            <?= $form->field($modelDiagnosticoInstrumento, 'instrumentoIds')->multiselect($optionsInstrumento) ?>
        <?php Pjax::end() ?>

        <div class="multiselect-footer">
            <?= Html::button('Outros', [
                'class' => 'btn btn-link p-0',
                'value' => Url::to(['@elaborar/diagnostico/outro-instrumento/create']),
                'id' => 'modalOutrosInstrumentos',
                'data-show' => 'modal-outro-instrumento'
            ]) ?>
        </div>
    </div>

    <?= $form->field($modelDiagnosticoResultado, 'descricao')->widget(TinyMce::className()); ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar e Próximo', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

Universal::modal('Outros', 'modal-outro-instrumento', 'modal-xl');

$js = <<<JS
    var instrumentosStorage = [];
    localStorage.removeItem('@InstrumentosIds');

    $("input[id*='instrumentoids']").change(function() {
        var value = $(this).val();
        if($(this).prop('checked')) {
            instrumentosStorage.push(value);
            localStorage.setItem('@InstrumentosIds', JSON.stringify(instrumentosStorage));
        } else {
            instrumentosStorage.splice(instrumentosStorage.indexOf(value), 1);
            localStorage.setItem('@InstrumentosIds', JSON.stringify(instrumentosStorage));
        }
    });
JS;

$this->registerJs($js);
