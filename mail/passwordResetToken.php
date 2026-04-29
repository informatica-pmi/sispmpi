<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
?>

<div class="password-reset">
    <p>Olá <?= $params['nome'] ?>,</p>
    
    <p>Siga o link abaixo para redefinir sua senha:</p>

    <p><?= Html::a(Html::encode($params['resetLink']), $params['resetLink']) ?></p>

    <p>
        Atenciosamente, <br /> Equipe SisPMPI
    </p>
</div>
