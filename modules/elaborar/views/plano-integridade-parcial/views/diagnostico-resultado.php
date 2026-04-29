<?php

use app\components\helpers\Universal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $modelDiagnosticoResultado app\modules\elaborar\models\DiagnosticoResultado */
/* @var $modelsDiagnosticoEixoTematico app\modules\elaborar\models\DiagnosticoEixoTematico[] */
?>
<div class="diagnostico-resultado-view">
    <h3>Programa de integridade</h3>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('objetivos_trabalhados'),
            Universal::stripTags($modelDiagnosticoResultado->objetivos_trabalhados)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('objetivos_estrategicos'),
            Universal::stripTags($modelDiagnosticoResultado->objetivos_estrategicos)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('estrutura_governanca'),
            Universal::stripTags($modelDiagnosticoResultado->estrutura_governanca)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('periodicidade_monitoramentos'),
            $modelDiagnosticoResultado->periodicidade_monitoramentos
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('periodicidade_avaliacoes'),
            $modelDiagnosticoResultado->periodicidade_avaliacoes
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('periodicidade_atualizacoes'),
            $modelDiagnosticoResultado->periodicidade_atualizacoes
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('aspectos_comunicacao'),
            Universal::stripTags($modelDiagnosticoResultado->aspectos_comunicacao)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('aspectos_capacitacao'),
            Universal::stripTags($modelDiagnosticoResultado->aspectos_capacitacao)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong><br>%s',
            $modelDiagnosticoResultado->getAttributeLabel('eixoTematicoIds'),
            implode(", ", ArrayHelper::getColumn($modelsDiagnosticoEixoTematico, function ($diagnosticoEixoTematico) {
                $eixoTematico = $diagnosticoEixoTematico->eixoTematico;

                return is_null($eixoTematico->orgao_id) ?
                    $eixoTematico->nome :
                    "<span class='text-specific'>{$eixoTematico->nome}</span>";
            }))
        ); ?>
    </p>

</div>
