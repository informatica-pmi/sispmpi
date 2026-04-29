<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="auditor-monitoramento-publicacao">
    <p>
        Prezado(a) <?= $params['nome'] ?>,
    </p>

    <p>
        O plano de integridade da instituição <strong><?= $params['nomeOrgao'] ?></strong>
        foi concluído com sucesso. Até o momento não foi realizado o cadastro do usuário perfil monitoramento
    </p>

    <p>
        Verifique com a Alta Administração quem será responsável pelo monitoramento das ações do plano de integridade
        e proceda ao cadastro do mesmo no sistema.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
