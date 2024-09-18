<?php

use app\components\helpers\Universal;
use app\modules\monitorar\models\AcaoMonitoramento;

/* @var $this yii\web\View */
/* @var $modelAcaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
?>

<div class="pdf-monitoramento">
    <p class="my-1">
        <span class="font-weight-bold">Risco de não implementação:</span>

        <?= AcaoMonitoramento::getRisco($modelAcaoMonitoramento->risco_n_implementacao) ?>
    </p>

    <div class="card">
        <div class="card-body">
            <p class="font-weight-bold border-bottom">Recomendações</p>
            <?php if ($modelAcaoMonitoramento->acaoMonitoramentoRecomendacaos) : ?>
                <?php
                foreach ($modelAcaoMonitoramento->acaoMonitoramentoRecomendacaos as $index => $modelAcaoMonitoramentoRecomendacao) :
                    ?>
                    <div class="border-bottom">
                        <?php $newIndex = $index + 1; ?>

                        <p class="my-1 font-weight-bold"><?= $newIndex ?>° recomendação</p>

                        <p class="mb-3"><?= $modelAcaoMonitoramentoRecomendacao->recomendacao ?></p>

                        <p class="my-1 font-weight-bold">Resposta</p>

                        <p>
                            <?= Universal::valueField(
                                $modelAcaoMonitoramentoRecomendacao->resposta,
                                null,
                                true,
                                'Aguardando resposta.'
                            ) ?>
                        </p>
                    </div>

                <?php endforeach; ?>
            <?php else : ?>
                <p>Sem recomendações</p>
            <?php endif; ?>
        </div>
    </div>
</div>
