<?php

use app\components\helpers\Universal;
use app\modules\monitorar\models\AcaoMonitoramento;

/* @var $this yii\web\View */
/* @var $modelAcaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
/* @var $modelsAcaoMonitoramentoRecomendacao app\models\AcaoMonitoramentoRecomendacao */
?>

<div class="pdf-monitoramento">
    <p class="my-1">
        <span class="font-weight-bold">Risco de não implementação:</span>

        <?= !empty($modelAcaoMonitoramento->risco_n_implementacao) ?
            AcaoMonitoramento::getRisco($modelAcaoMonitoramento->risco_n_implementacao) :
            'Não se aplica/Não definido';
        ?>
    </p>

    <div class="card">
        <div class="card-body">
            <p class="font-weight-bold border-bottom">Recomendações do comitê de monitoramento</p>

            <?php $countRecomendacaos = count($modelsAcaoMonitoramentoRecomendacao); ?>

            <?php if ($countRecomendacaos > 0) : ?>
                <?php foreach ($modelsAcaoMonitoramentoRecomendacao as $index => $acaoMonitoramentoRecomendacao) : ?>
                    <?php $newIndex = $index + 1; ?>

                    <div class="border-bottom <?= $countRecomendacaos === $newIndex ? 'last-item' : ''?>">

                        <p class="my-1 font-weight-bold"><?= $newIndex ?>° recomendação</p>

                        <p class="mb-3"><?= $acaoMonitoramentoRecomendacao->recomendacao ?></p>

                        <p class="my-1 font-weight-bold">Resposta</p>

                        <p>
                            <?= Universal::valueField(
                                $acaoMonitoramentoRecomendacao->resposta,
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
