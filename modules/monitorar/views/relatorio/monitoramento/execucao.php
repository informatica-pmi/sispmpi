<?php

use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
?>
<div class="pdf-monitoramento-execucao">
    <p class="my-1">
        <span class="font-weight-bold">Observações gerais:</span>
        <?= Universal::valueField($modelAcaoExecucao->observacoes, null, true) ?>
    </p>

    <p class="my-1">
        <span class="font-weight-bold">Fatores limitantes:</span>
        <?= Universal::valueField(
            implode(
                ', ',
                ArrayHelper::getColumn(
                    $modelAcaoExecucao->acaoExecucaoFatorLimitantes,
                    'fatorLimitante.nome'
                )
            ),
            null,
            true,
            'Conteúdo não informado no plano de integridade do órgão'
        ) ?>
    </p>
</div>
