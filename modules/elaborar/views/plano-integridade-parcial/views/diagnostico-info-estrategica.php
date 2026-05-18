<?php

/* @var $this yii\web\View */
/* @var $modelInfoEstrategica app\modules\elaborar\models\DiagnosticoInfoEstrategica */

use app\components\helpers\Universal;

?>
<div class="diagnostico-info-estrategica-view">
    <h2>Programa de integridade</h2>

    <h3>Estrutura organizacional</h3>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelInfoEstrategica->getAttributeLabel('missao'),
            Universal::stripTags($modelInfoEstrategica->missao)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelInfoEstrategica->getAttributeLabel('visao'),
            Universal::stripTags($modelInfoEstrategica->visao)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelInfoEstrategica->getAttributeLabel('valores'),
            Universal::stripTags($modelInfoEstrategica->valores)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelInfoEstrategica->getAttributeLabel('estrutura_organica'),
            Universal::stripTags($modelInfoEstrategica->estrutura_organica)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelInfoEstrategica->getAttributeLabel('competencias'),
            Universal::stripTags($modelInfoEstrategica->competencias)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelInfoEstrategica->getAttributeLabel('atribuicoes'),
            Universal::stripTags($modelInfoEstrategica->atribuicoes)
        ); ?>
    </p>
</div>
