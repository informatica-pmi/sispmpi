<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $tipoNomes */
?>

<div class="informacao-view-only-reading">
    <p class="mb-1">
        <span class="font-weight-semi-bold">
            <?= $modelAcao->getAttributeLabel('eixo') ?>:
        </span>

        <?= $modelAcao->eixo->titulo ?>
    </p>

    <p class="mb-1">
        <span class="font-weight-semi-bold">
            <?= $modelAcao->getAttributeLabel('subeixo') ?>:
        </span>

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
        <span class="font-weight-semi-bold">
            <?= $modelAcao->getAttributeLabel('objetivo') ?>:
        </span>

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

    <div class="card border-primary bw-3 mb-0">
        <div class="card-body">
            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('classificacao') ?>:
                </span>

                <?= Universal::valueField($modelAcao->classificacao, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('tipo') ?>:
                </span>

                <?= Universal::valueField(implode(', ', $tipoNomes), null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('previsao_inicio') ?>
                </span>

                <?= Universal::valueField($modelAcao->previsao_inicio, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('previsao_conclusao') ?>:
                </span>

                <?= Universal::valueField($modelAcao->previsao_conclusao, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('orcamento_previsto') ?>:
                </span>

                <?= Universal::valueField($modelAcao->orcamento_previsto, null, true) ?>
            </p>
        </div>
    </div>
</div>
