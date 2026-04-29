<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<div class="executor-recomendacao">
    <p>Prezado(a),</p>

    <p>
        Informamos que foi realizada uma nova recomendação na ação
        <strong><?= mb_convert_case($params['tituloAcao'], MB_CASE_TITLE, "UTF-8") ?></strong>
        do Plano de Integridade do seu órgão/entidade.
    </p>

    <p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
