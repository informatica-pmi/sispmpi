<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="monitoramento-publicacao">
    <p>Prezado(a) <?= $params['nome'] ?>,</p>

    <p>O plano de integridade da instituição <strong><?= $params['nomeOrgao'] ?></strong> foi concluído com sucesso.</p>

    <p>A próxima etapa é a execução das ações estabelecidas no plano.</p>

    <p>
        Para que seja dado início a execução das ações do plano de integridade, procure as unidades administrativas
        responsáveis pelas ações (registradas no plano de ação) e confirme os dados dos servidores que ficarão responsáveis
        pela execução de cada ação.
    </p>

    <p>
        Anote o nome, Masp/matrícula, telefone institucional, e-mail institucional, cargo e unidade administrativa e
        repasse essas informações para o Auditor, para que o mesmo proceda ao cadastro dos responsáveis pela execução
        de cada ação.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>