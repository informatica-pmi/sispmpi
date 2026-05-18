<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelsPlanoPromover app\modules\avaliar\models\Promover[] */
?>
<div class="pdf-controle-interno-promover">
    <?php $arrayKeyLast = array_key_last($modelsPlanoPromover); ?>

    <?php foreach ($modelsPlanoPromover as $index => $planoPromover) : ?>
        <div class="border-bottom <?= $arrayKeyLast === $index ? 'last-item' : ''?>">
            <p class="my-1 font-weight-bold"><?= $planoPromover->getAttributeLabel('acao_desenvolvida') ?></p>

            <p class="mb-3">
                <?= $planoPromover->acao_desenvolvida ?>
            </p>

            <p class="my-1 font-weight-bold"><?= $planoPromover->getAttributeLabel('data') ?></p>

            <p class="mb-3"><?= Universal::convertDate($planoPromover->data) ?></p>

            <p class="my-1 font-weight-bold">
                <?= $planoPromover->getAttributeLabel('horas_trabalho') ?>
            </p>

            <p><?= $planoPromover->horas_trabalho ?></p>
        </div>
    <?php endforeach; ?>
</div>
