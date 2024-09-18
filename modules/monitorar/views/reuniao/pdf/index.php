<?php

/* @var $modelReuniaoReuniao app\modules\monitorar\models\Reuniao */
?>

<div class="pdf-reuniao-view">
    <h3 class="border-bottom"><?= $modelReuniao->nome ?></h3>

    <p>
        <span class="font-weight-bold"><?= $modelReuniao->getAttributeLabel('data') ?>:</span>
        <span><?= $modelReuniao->data ?></span>
    </p>

    <p class="mb-3">
        <span class="font-weight-bold">Nome dos participantes da reunião:</span>
        <span><?= $servidorsNome ?></span>
    </p>

    <p class="font-weight-bold"><?= $modelReuniao->getAttributeLabel('pauta') ?>:</span></p>
    <?= $modelReuniao->pauta ?>

    <div class="mb-3"></div>

    <p class="font-weight-bold"><?= $modelReuniao->getAttributeLabel('registro') ?>:</p>
    <?= $modelReuniao->registro ?>
</div>
