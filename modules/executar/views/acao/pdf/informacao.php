<?php

use app\components\helpers\Universal;

/* @var $modelAcao app\models\Acao */
?>

<div class="card">
    <div class="card-header">
        Informações Gerais da Ação
    </div>

    <div class="card-body">
        <p class="my-1">
            <span class="font-weight-bold">Eixo:</span>

            <?= $modelAcao->eixo->titulo ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Subeixo:</span>

            <?= Universal::valueField(
                $modelAcao->subeixo,
                'titulo',
                true,
                'Conteúdo não informado no Plano de Integridade do órgão',
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Identificador da ação:</span>

            <?= $modelAcao->numero ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Objetivo da ação:</span>

            <?= Universal::valueField(
                $modelAcao->objetivo,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Benefícios esperados:</span>

            <?= Universal::valueField(
                $modelAcao->beneficio_instituicao,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Detalhamento da ação:</span>

            <?= $modelAcao->descricao ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Classificação:</span>

            <?= Universal::valueField(
                $modelAcao->classificacao,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Tipo:</span>

            <?= Universal::valueField(
                implode(", ", $modelAcao->tipoIds),
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Previsão de início da ação:</span>

            <?= $modelAcao->previsao_inicio ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Previsão de conclusão da ação:</span>

            <?= $modelAcao->previsao_conclusao ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Orçamento previsto:</span>

            <?= Universal::convertCurrency($modelAcao->orcamento_previsto) ?>
        </p>
    </div>
</div>
