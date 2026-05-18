<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsAcaoAvaliacaoRecomendacao app\models\AcaoAvaliacaoRecomendacao */
?>

<div class="avaliacao-view">
    <div class="card border-primary bw-3 mb-0">
        <div class="card-body">
            <?php foreach ($modelsAcaoAvaliacaoRecomendacao as $index => $modelAcaoAvaliacaoRecomendacao) : ?>
                <?php $classDiv = $index != 0 ? 'mt-3 pt-3 border-top' : ''; ?>

                <div class="<?= $classDiv ?>">
                    <p class="mb-1">
                        <span class="d-block font-weight-semi-bold">
                            <?= sprintf(
                                '%sº %s',
                                $index + 1,
                                $modelAcaoAvaliacaoRecomendacao->getAttributeLabel('recomendacao')
                            ) ?>
                        </span>

                        <?= $modelAcaoAvaliacaoRecomendacao->recomendacao ?>
                    </p>

                    <p class="mb-0 mt-2">
                        <span class="d-block font-weight-semi-bold">
                            <?= $modelAcaoAvaliacaoRecomendacao->getAttributeLabel('resposta') ?>
                        </span>

                        <?= Universal::valueField(
                            $modelAcaoAvaliacaoRecomendacao->resposta,
                            null,
                            true
                        ) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
