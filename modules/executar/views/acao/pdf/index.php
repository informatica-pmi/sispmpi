<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $userOrgaoNome */
/* @var $modelAcao app\models\Acao */
/* @var $modelAcaoExecucao app\models\modelAcaoExecucao */
/* @var $servidorResponsavel */
/* @var $servidorsEnvolvido app\models\Servidor */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>
        <div>
            <header class="mb-3">
                <h5><?= $userOrgaoNome ?></h5>

                <h6>Ação: <?= $modelAcao->titulo ?></h6>
            </header>
        </div>

        <?= $this->render('informacao', [
            'modelAcao' => $modelAcao,
        ]) ?>

        <?= $this->render('responsabilidade', [
            'modelAcao' => $modelAcao,
            'servidorResponsavel' => $servidorResponsavel,
            'servidorsEnvolvido' => $servidorsEnvolvido
        ]) ?>

        <?php
        if ($modelAcaoExecucao) :
            echo $this->render('execucao', [
                'modelAcaoExecucao' => $modelAcaoExecucao
            ]);
        endif;
        ?>

        <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>
