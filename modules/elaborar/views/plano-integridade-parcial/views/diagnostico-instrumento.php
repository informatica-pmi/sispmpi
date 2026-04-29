<?php

use app\components\helpers\Universal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $modelsDiagnosticoInstrumento app\modules\elaborar\models\DiagnosticoInstrumento[] */
/* @var $modelDiagnosticoResultado app\modules\elaborar\models\DiagnosticoResultado */
?>
<div class="diagnostico-instrumento-view"
    <h3>Diagnóstico do ambiente de integridade</h3>

    <p>
        <?= sprintf(
            '<strong>%s</strong><br>%s',
            $modelsDiagnosticoInstrumento[0]->getAttributeLabel('instrumentoIds'),
            implode(", ", ArrayHelper::getColumn($modelsDiagnosticoInstrumento, function ($diagnosticoInstrumento) {
                $instrumento = $diagnosticoInstrumento['instrumento'];

                return is_null($instrumento['orgao_id']) ?
                    $instrumento['nome'] :
                    "<span class='text-specific'>{$instrumento['nome']}</span>";
            }))
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelDiagnosticoResultado->getAttributeLabel('descricao'),
            Universal::stripTags($modelDiagnosticoResultado->descricao)
        ); ?>
    </p>

</div>
