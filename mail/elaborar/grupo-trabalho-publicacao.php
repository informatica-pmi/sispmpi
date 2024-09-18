<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="grupo-trabalho-publicacao">
    <p>
        Prezado(a) <?= $params['nome'] ?>,
    </p>

    <p>
        O plano de integridade da instituição <strong><?= $params['nomeOrgao'] ?></strong>
        foi concluído com sucesso.
    </p>

    <p>
        Agradecemos o empenho para a finalização do mesmo, neste momento estamos desativando o seu usuário.
    </p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>