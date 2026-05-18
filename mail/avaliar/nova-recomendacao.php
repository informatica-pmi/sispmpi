<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div class="nova-recomendacao">
    <p>
        Prezado(a), informamos que foi realizada uma nova recomendação na ação
        <strong><?= mb_convert_case($params['tituloAcao'], MB_CASE_TITLE, "UTF-8") ?></strong> do plano de integridade
        pela unidade de controle interno do seu órgão/entidade.
    </p>

    <p>
        Atenciosamente,
        <br />
        Equipe SisPMPI
    </p>
</div>
