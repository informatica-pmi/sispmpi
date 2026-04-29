<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $acaoMonitoramento app\modules\monitorar\models\AcaoMonitoramento */
?>

<div class="monitoramento-view-only-reading">
    <p class="mb-3">
        <span class="font-weight-semi-bold">
            <?= $acaoMonitoramento->getAttributeLabel('risco_n_implementacao') ?>:
        </span>

        <?= Universal::valueField(
            $acaoMonitoramento->risco_n_implementacao,
            null,
            true
        ) ?>
    </p>
</div>
