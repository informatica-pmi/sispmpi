<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use app\components\helpers\Universal;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelGrupo app\modules\elaborar\models\Grupo */

$firstGrupoInstituido = $modelGrupo->firstGrupoInstituido;
$extensionsGrupo = $modelGrupo->withoutFirstGrupoInstituido;
?>
<div class="grupo-instituido-view">

    <h2>Instituição da comissão de integridade</h2>

    <?php if ($firstGrupoInstituido->formalmente === Status::STATUS_NAO) : ?>
        <?= sprintf(
            '%s: %s',
            $firstGrupoInstituido->getAttributeLabel('data_inicio'),
            Universal::convertDate($firstGrupoInstituido->data_inicio)
        ); ?>
    <?php else : ?>
        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $firstGrupoInstituido->getAttributeLabel('nome_numero'),
                $firstGrupoInstituido->nome_numero
            ); ?>
        </p>

        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $firstGrupoInstituido->getAttributeLabel('data_publicacao'),
                Universal::convertDate($firstGrupoInstituido->data_publicacao)
            ); ?>
        </p>

        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $firstGrupoInstituido->getAttributeLabel('data_prevista_conclusao'),
                Universal::convertDate($firstGrupoInstituido->data_prevista_conclusao)
            ); ?>
        </p>

        <p>
            <?= sprintf(
                '<strong>%s</strong>: %s',
                $firstGrupoInstituido->getAttributeLabel('link'),
                Html::a($firstGrupoInstituido->link, $firstGrupoInstituido->link)
            ); ?>
        </p>
    <?php endif; ?>

    <?php if ($extensionsGrupo) : ?>
        <?php foreach ($extensionsGrupo as $extensionGrupo) : ?>
            <h3>Prorrogação do Prazo #<?= $extensionGrupo->order ?></h3>
            <p>
                <?= sprintf(
                    '<strong>%s</strong>: %s',
                    $extensionGrupo->getAttributeLabel('nome_numero'),
                    $extensionGrupo->nome_numero
                ); ?>
            </p>

            <p>
                <?= sprintf(
                    '<strong>%s</strong>: %s',
                    $extensionGrupo->getAttributeLabel('data_publicacao'),
                    Universal::convertDate($extensionGrupo->data_publicacao)
                ); ?>
            </p>

            <p>
                <?= sprintf(
                    '<strong>%s</strong>: %s',
                    $extensionGrupo->getAttributeLabel('data_prevista_conclusao'),
                    Universal::convertDate($extensionGrupo->data_prevista_conclusao)
                ); ?>
            </p>

            <p>
                <?= sprintf(
                    '<strong>%s</strong>: %s',
                    $extensionGrupo->getAttributeLabel('link'),
                    Html::a($extensionGrupo->link, $extensionGrupo->link)
                ); ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
