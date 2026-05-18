<?php

/* @var $this yii\web\View */
/* @var $modelDiagnostico app\modules\elaborar\models\Diagnostico */
/* @var $modelDiagnosticoInfoEstrategica app\modules\elaborar\models\DiagnosticoInfoEstrategica */
/* @var $modelDiagnosticoResultado app\modules\elaborar\models\DiagnosticoResultado */
/* @var $modelDiagnosticoResultadoPrograma app\modules\elaborar\models\DiagnosticoResultado */
/* @var $modelDiagnosticoInstrumento app\modules\elaborar\models\DiagnosticoInstrumento */
/* @var $modelsInstrumento app\models\Instrumento[] */
/* @var $optionsInstrumento app\models\Instrumento[] */
/* @var $optionsEixoTematico app\models\EixoTematico[] */
/* @var $prepareTabs */

$this->title = 'Diagnóstico';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="diagnostico-update">

    <?= $this->render('_form', [
        'modelDiagnostico' => $modelDiagnostico,
        'modelDiagnosticoInfoEstrategica' => $modelDiagnosticoInfoEstrategica,
        'modelDiagnosticoResultado' => $modelDiagnosticoResultado,
        'modelDiagnosticoResultadoPrograma' => $modelDiagnosticoResultadoPrograma,
        'modelDiagnosticoInstrumento' => $modelDiagnosticoInstrumento,
        'modelsInstrumento' => $modelsInstrumento,
        'optionsInstrumento' => $optionsInstrumento,
        'optionsEixoTematico' => $optionsEixoTematico,
        'prepareTabs' => $prepareTabs
    ]) ?>

</div>
