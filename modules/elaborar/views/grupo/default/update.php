<?php

/* @var $this yii\web\View */
/* @var $modelGrupo app\models\Grupo */
/* @var $modelGrupoInstituido app\modules\elaborar\models\GrupoInstituido */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $modelGrupoInstituido app\modules\elaborar\models\GrupoInstituido[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $prepareGrupoServidor */
/* @var $ordersGrupoServidorUpdate */

$this->title = 'Instituição da comissão de integridade';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="grupo-update">

    <?= $this->render('_form', [
        'modelGrupo' => $modelGrupo,
        'modelGrupoInstituido' => $modelGrupoInstituido,
        'modelsServidor' => $modelsServidor,
        'modelsGrupoInstituido' => $modelsGrupoInstituido,
        'prepareGrupoServidor' => $prepareGrupoServidor,
        'ordersGrupoServidorUpdate' => $ordersGrupoServidorUpdate,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
    ]) ?>

</div>
