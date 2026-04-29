<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="user">
	<p>Olá <?= $params['nome'] ?>, </p>

	<p>Você foi cadastrado no SisPMPI, segue abaixo as credenciais para acesso ao sistema: </p>

	<p>
		<span><strong>Login</strong> <?= $params['login'] ?></span><br />
		<span><strong>Senha</strong>: <?= $params['senha'] ?></span>
	</p>

	<p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

	<p>
		Atenciosamente, <br /> Equipe SisPMPI
	</p>
</div>