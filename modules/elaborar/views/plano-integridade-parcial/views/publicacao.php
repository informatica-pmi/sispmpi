<?php

use yii\helpers\Html;
use app\components\helpers\Universal;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelPublicacao app\modules\elaborar\models\Publicacao */
?>
<div class="publicacao-view">
    <h2>Publicação do programa e do plano de integridade</h2>

    <p><strong><?= $modelPublicacao->getAttributeLabel('evento') ?></strong></p>

    <p><?= Status::getResposta($modelPublicacao->evento) ?></p>

    <?php if ($modelPublicacao->evento === Status::STATUS_SIM) : ?>
        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $modelPublicacao->getAttributeLabel('data_evento'),
                Universal::convertDate($modelPublicacao->data_evento)
            ); ?>
        </p>
    <?php else : ?>
        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $modelPublicacao->getAttributeLabel('justificativa_evento'),
                $modelPublicacao->justificativa_evento
            ); ?>
        </p>
    <?php endif; ?>

    <p><strong><?= $modelPublicacao->getAttributeLabel('disponibilizado') ?></strong></p>

    <p><?= Status::getResposta($modelPublicacao->disponibilizado) ?></p>

    <?php if ($modelPublicacao->disponibilizado === Status::STATUS_SIM) : ?>
        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $modelPublicacao->getAttributeLabel('endereco_disponibilizado'),
                Html::a($modelPublicacao->endereco_disponibilizado, $modelPublicacao->endereco_disponibilizado)
            ); ?>
        </p>
    <?php else : ?>
        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $modelPublicacao->getAttributeLabel('justificativa_disponibilizado'),
                $modelPublicacao->justificativa_disponibilizado
            ); ?>
        </p>
    <?php endif; ?>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelPublicacao->getAttributeLabel('data_publicacao'),
            Universal::convertDate($modelPublicacao->data_publicacao)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelPublicacao->getAttributeLabel('nome_numero'),
            $modelPublicacao->nome_numero
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelPublicacao->getAttributeLabel('link'),
            Html::a($modelPublicacao->link, $modelPublicacao->link)
        ); ?>
    </p>
</div>
