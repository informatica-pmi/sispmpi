<?php

/* @var $this yii\web\View */
/* @var $modelDiagnostico app\modules\elaborar\models\Diagnostico */
/* @var $modelDiagnosticoInfoEstrategica app\modules\elaborar\models\DiagnosticoInfoEstrategica */
/* @var $prepareTabs [] */

$this->title = 'Diagnóstico';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Cadastrar';
?>
<div class="diagnostico-create">

    <?= $this->render('_form', [
        'modelDiagnostico' => $modelDiagnostico,
        'modelDiagnosticoInfoEstrategica' => $modelDiagnosticoInfoEstrategica,
        'prepareTabs' => $prepareTabs,
    ]) ?>

</div>
