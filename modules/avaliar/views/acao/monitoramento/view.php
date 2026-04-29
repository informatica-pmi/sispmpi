<?php

use app\components\helpers\Universal;
use app\models\AcaoMonitoramentoRecomendacao;
use app\modules\monitorar\models\AcaoMonitoramento;

/* @var $this yii\web\View */
/* @var $modelAcaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao[] */
?>

<div class="monitoramento-view">
    <div class="card border-primary bw-3 mb-0">
        <div class="card-body">
            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcaoMonitoramento->getAttributeLabel('risco_n_implementacao') ?>:
                </span>

                <?= Universal::valueField(
                    !empty($modelAcaoMonitoramento->risco_n_implementacao) ?
                        AcaoMonitoramento::getRisco($modelAcaoMonitoramento->risco_n_implementacao) :
                        '',
                    null,
                    true
                ) ?>
            </p>

            <h6 class="font-weight-semi-bold">Recomendações</h6>
            <?php if (!empty($modelsAcaoMonitoramentoRecomendacao)) : ?>
                <div class="card mb-0">
                    <div class="card-body">
                        <?php
                        foreach (
                            $modelsAcaoMonitoramentoRecomendacao as $index => $modelAcaoMonitoramentoRecomendacao
                        ) :
                            ?>
                            <?php $classDiv = $index != 0 ? 'mt-3 pt-3 border-top' : ''; ?>

                            <div class="<?= $classDiv ?>">
                                <p class="mb-1">
                                    <span class="d-block font-weight-semi-bold">
                                        <?= sprintf(
                                            "%sº %s",
                                            $index + 1,
                                            $modelAcaoMonitoramentoRecomendacao->getAttributeLabel('recomendacao')
                                        ) ?>
                                    </span>

                                    <?= $modelAcaoMonitoramentoRecomendacao->recomendacao ?>
                                </p>

                                <p class="mb-0 mt-2">
                                    <span class="d-block font-weight-semi-bold">
                                        <?= $modelAcaoMonitoramentoRecomendacao->getAttributeLabel('resposta') ?>
                                    </span>

                                    <?= Universal::valueField(
                                        $modelAcaoMonitoramentoRecomendacao->resposta,
                                        null,
                                        true
                                    ) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="card mb-0">
                    <div class="card-body">
                        <span>Nenhuma recomendação cadastrada.</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
