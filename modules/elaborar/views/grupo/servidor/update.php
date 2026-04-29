<?php

/* @var $this yii\web\View */
/* @var $models app\models\Servidor[] */
/* @var $modelsGrupoServidor app\models\GrupoServidor[] */
/* @var $modelGrupoInstituido app\modules\elaborar\GrupoInstituido */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
?>
<div class="servidor-update">

    <?= $this->render('form/_aditional', [
        'modelsServidor' => $modelsServidor,
        'modelsGrupoServidor' => $modelsGrupoServidor,
        'modelGrupoInstituido' => $modelGrupoInstituido,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
    ]) ?>

</div>
