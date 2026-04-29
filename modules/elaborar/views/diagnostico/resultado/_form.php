<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\components\widgets\TinyMCE;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelDiagnosticoResultadoPrograma app\modules\elaborar\models\DiagnosticoResultado */
/* @var $diagnosticoId */
/* @var $optionsEixoTematico */
/* @var $form kartik\form\ActiveForm */
?>

<div class="diagnostico-resultado-form">

    <?php $form = ActiveForm::begin([
        'action' => ['@elaborar/diagnostico/resultado/create', 'diagnosticoId' => $diagnosticoId]
    ]); ?>

    <?= $form->field($modelDiagnosticoResultadoPrograma, 'objetivos_trabalhados')->widget(TinyMce::className()) ?>

    <?= $form->field($modelDiagnosticoResultadoPrograma, 'objetivos_estrategicos')->widget(TinyMce::className()) ?>

    <?= $form->field($modelDiagnosticoResultadoPrograma, 'estrutura_governanca')->widget(TinyMce::className()); ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($modelDiagnosticoResultadoPrograma, 'periodicidade_monitoramentos')
                        ->textInput(['maxlength' => true])
                    ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelDiagnosticoResultadoPrograma, 'periodicidade_avaliacoes')
                        ->textInput(['maxlength' => true])
                    ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelDiagnosticoResultadoPrograma, 'periodicidade_atualizacoes')
                        ->textInput(['maxlength' => true])
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?= $form->field($modelDiagnosticoResultadoPrograma, 'aspectos_comunicacao')->widget(TinyMce::className()) ?>

    <?= $form->field($modelDiagnosticoResultadoPrograma, 'aspectos_capacitacao')->widget(TinyMce::className()) ?>

    <div class="multiselect-container mb-3">
        <?php Pjax::begin(['id' => 'container-eixos-tematicos']) ?>
            <?= $form->field($modelDiagnosticoResultadoPrograma, 'eixoTematicoIds')->multiselect($optionsEixoTematico) ?>
        <?php Pjax::end() ?>

        <div class="multiselect-footer">
            <?= Html::button('Outros', [
                'class' => 'btn btn-link p-0',
                'value' => Url::to(['@elaborar/diagnostico/outro-eixo-tematico/create']),
                'id' => 'modalOutrosEixosTematicos',
                'data-show' => 'modal-outro-eixo-tematico'
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

Universal::modal('Outros', 'modal-outro-eixo-tematico', 'modal-xl');

$js = <<<JS
    var eixosTematicosStorage = [];
    localStorage.removeItem('@EixosTematicosIds');

    $("input[id*='eixotematicoids']").change(function() {
        var value = $(this).val();
        if($(this).prop('checked')) {
            eixosTematicosStorage.push(value);
            localStorage.setItem('@EixosTematicosIds', JSON.stringify(eixosTematicosStorage));
        } else {
            eixosTematicosStorage.splice(eixosTematicosStorage.indexOf(value), 1);
            localStorage.setItem('@EixosTematicosIds', JSON.stringify(eixosTematicosStorage));
        }
    });
JS;

$this->registerJs($js);
