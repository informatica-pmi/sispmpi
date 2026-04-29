<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="auditor-atualizacao">
    <p>
        Prezado(a),
    </p>

    <p>
        O comitê de monitoramento do plano de integridade do seu órgão/entidade solicitou a
        <?= $params['tipoSolicitacao'] ?>.
    </p>

    <p>
        Favor confirmar a operação com o coordenador do Comitê e informar à Alta Administração os procedimentos
        necessários para a atualização do Plano de Integridade.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
