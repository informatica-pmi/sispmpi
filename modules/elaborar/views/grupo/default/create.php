<?php

/* @var $this yii\web\View */
/* @var $modelGrupo app\models\Grupo */
/* @var $modelGrupoInstituido app\models\GrupoInstituido */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */

$this->title = 'Instituição da comissão de integridade';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Cadastrar';
?>
<div class="grupo-create">

    <?= $this->render('_form', [
        'modelGrupo' => $modelGrupo,
        'modelGrupoInstituido' => $modelGrupoInstituido,
        'modelsServidor' => $modelsServidor,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
    ]) ?>

</div>
