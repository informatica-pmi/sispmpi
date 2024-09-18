<?php

use app\components\helpers\Audit;
use app\models\Historico;

/* @var $this yii\web\View */
/* @var  $historicoAcaoExecucaos app\models\Historico[] */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
?>

<div class="pdf-execucao-acao">
    <?php array_map(function (Historico $historico) use ($modelAcaoExecucao) { ?>
        <?= Audit::prepareData(
            $historico,
            $modelAcaoExecucao->getAttributeLabel($historico->campo),
            $modelAcaoExecucao->behaviors
        ) ?>
    <?php }, $historicoAcaoExecucaos) ?>
</div>
