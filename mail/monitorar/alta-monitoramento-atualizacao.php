<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="alta-monitoramento-atualizacao">
    <p>
        Prezado(a),
    </p>

    <p>
        Informamos que o Comitê de Monitoramento do Plano de Integridade do seu órgão/entidade solicitou ao controle
        interno a atualização do Plano de Integridade.
    </p>

    <p>
        Será necessário definir os agentes públicos que comporão o Grupo de Trabalho para a <?= $params['tipoSolicitacao'] ?>.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
