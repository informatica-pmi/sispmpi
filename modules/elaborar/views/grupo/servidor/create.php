<?php

/* @var $this yii\web\View */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $modelsGrupoServidor app\modules\elaborar\models\GrupoServidor[] */
/* @var $modelGrupoInstituido app\modules\elaborar\GrupoInstituido */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */

$this->title = 'Create Servidor';
$this->params['breadcrumbs'][] = ['label' => 'Servidors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servidor-create">

    <?= $this->render('form/_aditional', [
        'modelsServidor' => $modelsServidor,
        'modelsGrupoServidor' => $modelsGrupoServidor,
        'modelGrupoInstituido' => $modelGrupoInstituido,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
    ]) ?>

</div>
