<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
?>

<div class="informacao-view-only-reading">
    <p class="mb-1">
        <span class="font-weight-semi-bold">Eixo:</span>

        <?= $modelAcao->eixo->titulo ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">Subeixo:</span>

        <?= Universal::valueField(
            $modelAcao->subeixo,
            'titulo',
            true,
            'Conteúdo não informado no Plano de Integridade do órgão'
        ) ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">Identificador da ação:</span>

        <?= $modelAcao->numero ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">Objetivo da ação:</span>

        <?= Universal::valueField(
            $modelAcao->objetivo,
            null,
            true,
            'Conteúdo não informado no Plano de Integridade do órgão'
        ) ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">Benefícios esperados:</span>

        <?= Universal::valueField(
            $modelAcao->beneficio_instituicao,
            null,
            true,
            'Conteúdo não informado no Plano de Integridade do órgão'
        ) ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">Detalhamento da ação:</span>

        <?= $modelAcao->descricao ?>
    </p>
</div>
