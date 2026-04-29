<?php

use app\components\helpers\Audit;
use app\models\Historico;

/* @var $this yii\web\View */
/* @var  $historicoAcaoExecucaos app\models\Historico[] */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
?>

<div class="pdf-execucao-acao">
    <?php array_map(function (Historico $historicoAcaoExecucao) use ($modelAcaoExecucao) { ?>
        <?= Audit::prepareData(
            $historicoAcaoExecucao,
            $modelAcaoExecucao->getAttributeLabel($historicoAcaoExecucao->campo),
            $modelAcaoExecucao->behaviors
        ) ?>
    <?php }, $historicoAcaoExecucaos) ?>
</div>
