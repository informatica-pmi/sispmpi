<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="alta-monitoramento-plano-integridade-novo">
    <p>
        Prezado(a),
    </p>

    <p>
        Informamos que o controle interno autorizou a atualização do plano de integridade do seu órgão/entidade.
        O módulo I do Sistema foi reaberto para a <?= $params['tipoSolicitacao'] ?>, favor definir os agentes publicos
        que comporão o grupo de trabalho para a atualização do plano de integridade.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
