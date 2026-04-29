<?php

use app\components\helpers\Universal;

/* @var $modelAcaoExecucao app\models\AcaoExecucao */
?>

<div class="card">
    <div class="card-header">
        Execução da Ação
    </div>

    <div class="card-body">
        <p class="my-1">
            <span class="font-weight-bold">Data de início:</span>
            <?= $modelAcaoExecucao->data_inicio ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Data de conclusão ou descontinuação:</span>
            <?= $modelAcaoExecucao->data_conclusao ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Orçamento executado:</span>
            <?= Universal::convertCurrency($modelAcaoExecucao->orcamento_executado) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Observações gerais:</span>
            <?= Universal::valueField(
                $modelAcaoExecucao->observacoes,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Fatores limitantes:</span>
            <?= Universal::valueField(
                implode(", ", $modelAcaoExecucao->fatoresLimitantesIds),
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Evidências sugeridas para controle da execução da ação:</span>
            <?= Universal::valueField(
                $modelAcaoExecucao->evidencias_sugeridas,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Evidências que comprovam a execução da ação (link):</span>
            <?= Universal::valueField(
                $modelAcaoExecucao->evidencia_link,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Processo eletrônico SEI ou link externo:</span>
            <?= Universal::valueField(
                $modelAcaoExecucao->processo_sei,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>
    </div>
</div>
