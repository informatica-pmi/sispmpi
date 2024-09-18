<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsAcaoAvaliacaoRecomendacao app\modules\monitorar\models\AcaoMonitoramento */
?>

<div class="pdf-monitoramento-controle-interno">
    <div class="card">
        <div class="card-body">
            <p class="font-weight-bold border-bottom">Recomendações do controle interno</p>

            <?php $countRecomendacaos = count($modelsAcaoAvaliacaoRecomendacao); ?>

            <?php if ($countRecomendacaos > 0) : ?>
                <?php foreach ($modelsAcaoAvaliacaoRecomendacao as $index => $acaoAvaliacaoRecomendacao) : ?>
                    <?php $newIndex = $index + 1; ?>

                    <div class="border-bottom <?= $countRecomendacaos === $newIndex ? 'last-item' : ''?>">

                        <p class="my-1 font-weight-bold"><?= $newIndex ?>° recomendação</p>

                        <p class="mb-3"><?= $acaoAvaliacaoRecomendacao->recomendacao ?></p>

                        <p class="my-1 font-weight-bold">Resposta</p>

                        <p>
                            <?= Universal::valueField(
                                $acaoAvaliacaoRecomendacao->resposta,
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
