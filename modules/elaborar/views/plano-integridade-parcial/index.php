<?php

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=programa_integridade_parcial.doc");

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelPlano app\models\PlanoIntegridade */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

    <style>
        .text-center {
            text-align: center;
        }
        h2, h3, h4 {
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-weight: 500;
        }
        h2 {
            color: #2E74B5;
        }
        h3, h4 {
            color: #444;
        }
        p {
            margin: 2px;
        }
        span.text-specific {
            color: #28a745;
        }
        div.child {
            margin-left: 30px;
        }
    </style>
</head>
<body style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
<?php $this->beginBody() ?>
    <?php if ($modelPlano->grupo) :
        echo $this->render('./views/grupo-instituido', [
            'modelGrupo' => $modelPlano->grupo
        ]);

        echo $this->render('./views/grupo-servidor', [
            'modelGrupo' => $modelPlano->grupo
        ]);
    endif; ?>

    <?php if ($modelPlano->diagnostico) :
        if ($modelPlano->diagnostico->diagnosticoInfoEstrategica) :
            echo $this->render('./views/diagnostico-info-estrategica', [
                'modelInfoEstrategica' => $modelPlano->diagnostico->diagnosticoInfoEstrategica
            ]);
        endif;

        if ($modelPlano->diagnostico->diagnosticoInstrumentos) :
            echo $this->render('./views/diagnostico-instrumento', [
                'modelsDiagnosticoInstrumento' => $modelPlano->diagnostico->diagnosticoInstrumentos,
                'modelDiagnosticoResultado' => $modelPlano->diagnostico->diagnosticoResultado,
            ]);
        endif;

        if ($modelPlano->diagnostico->diagnosticoResultado) :
            echo $this->render('./views/diagnostico-resultado', [
                'modelDiagnosticoResultado' => $modelPlano->diagnostico->diagnosticoResultado,
                'modelsDiagnosticoEixoTematico' => $modelPlano->diagnostico->diagnosticoEixoTematicos,
            ]);
        endif;
    endif; ?>

    <?php if ($modelPlano->eixos) :
        echo $this->render('./views/redacao', [
            'modelsEixo' => $modelPlano->eixos
        ]);
    endif; ?>

    <?php if ($modelPlano->validacao) :
        echo $this->render('./views/validacao', [
            'modelValidacao' => $modelPlano->validacao
        ]);
    endif; ?>

    <?php if ($modelPlano->publicacao) :
        echo $this->render('./views/publicacao', [
            'modelPublicacao' => $modelPlano->publicacao
        ]);
    endif; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
