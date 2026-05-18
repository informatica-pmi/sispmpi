<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsAcao app\models\Acao[] */
/* @var $withFilter */
/* @var $dataInicio */
/* @var $dataFim */
?>
<div class="pdf-controle-interno-avaliacao">
    <div class="card-body pt-0">
        <?php foreach ($modelsAcao as $acao) : ?>
            <?php $modelsAcaoAvaliacaoRecomendacao = $withFilter ?
                $acao->getPeriodAcaoAvaliacaoRecomendacaos($dataInicio, $dataFim)->all() :
                $acao->acaoAvaliacaoRecomendacaos;
            ?>

            <div class="card">
                <div class="card-body">
                    <p class="font-weight-bold border-bottom">Ação <?= $acao->numero ?>: <?= $acao->titulo ?></p>

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
        <?php endforeach; ?>
    </div>
</div>
