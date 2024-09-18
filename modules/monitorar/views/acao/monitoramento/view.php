<?php

use app\components\helpers\Universal;
use app\models\AcaoMonitoramentoRecomendacao;

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

                <?= Universal::valueField($modelAcaoMonitoramento->risco_n_implementacao, null, true) ?>
            </p>

            <h6 class="font-weight-semi-bold">Recomendações</h6>

            <?php if (!$modelsAcaoMonitoramentoRecomendacao[0]->isNewRecord) : ?>
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($modelsAcaoMonitoramentoRecomendacao as $index => $modelAcaoMonitoramentoRecomendacao) : ?>
                            <?php $classDiv = $index != 0 ? 'mt-3 pt-3 border-top' : ''; ?>

                            <div class="<?= $classDiv ?>">
                                <p class="mb-1">
                                    <span class="d-block font-weight-semi-bold">
                                        <?= $index + 1 ?>° recomendação
                                    </span>

                                    <span><?= $modelAcaoMonitoramentoRecomendacao->recomendacao ?></span>
                                </p>

                                <p class="mb-0 mt-2">
                                    <span class="d-block font-weight-semi-bold">
                                        <?= $modelAcaoMonitoramentoRecomendacao->getAttributeLabel('resposta') ?>
                                    </span>

                                    <span>
                                        <?= Universal::valueField(
                                            $modelAcaoMonitoramentoRecomendacao->resposta,
                                            null,
                                            true
                                        ) ?>
                                    </span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="card">
                    <div class="card-body">
                        <span class="text-muted">Nenhuma recomendação cadastrada.</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
