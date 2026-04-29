<?php

use app\components\helpers\Audit;

/* @var $this yii\web\View */
/* @var $historicoServidors app\models\Historico[] */
/* @var $modelServidor app\models\Servidor */
/* @var $acaoId */
/* @var $countServidors */
/* @var $oldRegister */
/* @var $closeRegister */
?>

<div class="pdf-historico-servidor">
    <?php foreach ($historicoServidors as $index => $historicoServidor) : ?>
        <?php $lastRegister = $index + 1 === $countServidors ?>

        <?php $closeRegister = !empty($oldRegister) && $oldRegister != $historicoServidor->id_registro ? 1 : 0 ?>

        <?= Audit::prepareDataServidor(
            $historicoServidor,
            $acaoId,
            $modelServidor->getAttributeLabel($historicoServidor->campo),
            $oldRegister,
            $closeRegister,
            $lastRegister,
        ) ?>

        <?php $oldRegister = $historicoServidor->id_registro ?>
    <?php endforeach ?>
</div>
