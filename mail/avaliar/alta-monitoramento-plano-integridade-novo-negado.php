<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="alta-monitoramento-plano-integridade-novo-negado">
    <p>
        Prezado(a),
    </p>

    <p>
        Informamos que o controle interno não autorizou a <?= $params['tipoSolicitacao'] ?> do seu órgão/entidade.
    </p>

    <p>
        Justificativa: <?= $params['justificativa'] ?>
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
