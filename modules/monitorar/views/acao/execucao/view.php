<?php

use yii\helpers\Html;
use app\components\helpers\Universal;
use app\models\Status;

/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
/* @var $fatorLimitanteNomes */
?>

<div class="execucao-view-only-reading">
    <div class="card border-primary bw-3 mb-0">
        <div class="card-body">
            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('status') ?>:
                </span>

                <?= Status::getAcaoStatus($modelAcao->status) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('data_inicio') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->data_inicio, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('data_conclusao') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->data_conclusao, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('orcamento_executado') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->orcamento_executado, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('observacoes') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->observacoes, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('fatoresLimitantesIds') ?>:
                </span>

                <?= Universal::valueField(
                    implode(', ', $fatorLimitanteNomes),
                    null,
                    true
                ) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('evidencias_sugeridas') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->evidencias_sugeridas, null, true) ?>
            </p>

            <hr>

            <div class="card">
                <div class="card-body">
                    <h6 class="font-weight-semi-bold">Evidências que comprovam a execução da ação</h6>

                    <p class="mb-1">
                        <span class="font-weight-semi-bold">
                            <?= $modelAcaoExecucao->getAttributeLabel('evidencia_link') ?>:
                        </span>

                        <?= Universal::valueField(
                            $modelAcaoExecucao->evidencia_link ?
                                Html::a(
                                    'Acessar',
                                    $modelAcaoExecucao->evidencia_link,
                                    ['target' => '_blank', 'rel' => 'noreferrer noopener']
                                ) :
                                '',
                            null,
                            true
                        ) ?>
                    </p>

                    <p class="mb-1">
                        <span class="font-weight-semi-bold">
                            <?= $modelAcaoExecucao->getAttributeLabel('evidenciaFiles') ?>:
                        </span>

                        <?php if ($modelAcaoExecucao->acaoExecucaoArquivos) : ?>
                            <?php $acaoExecucaoArquivos = $modelAcaoExecucao->acaoExecucaoArquivos; ?>

                            <?php foreach ($acaoExecucaoArquivos as $index => $acaoExecucaoEvidenciaFile) : ?>
                                <?php $arquivo = $acaoExecucaoEvidenciaFile->arquivo; ?>

                                <?= Html::a(
                                    $index > 0 ? ", {$arquivo->nome_original}" : $arquivo->nome_original,
                                    ['/arquivo/download', 'token' => $arquivo->token]
                                ) ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span>Não se aplica/Não definido</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <hr>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoExecucao->getAttributeLabel('processo_sei') ?>:
                </span>

                <?= Universal::valueField($modelAcaoExecucao->processo_sei, null, true) ?>
            </p>
        </div>
    </div>
</div>
