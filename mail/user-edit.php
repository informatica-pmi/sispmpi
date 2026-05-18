<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="user">
	<p>Prezado(a) <?= $params['nome'] ?>,</p>

	<p>Informamos que houve alterações recentes nos dados cadastrados em seu perfil do SisPMPI.</p>

	<p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

	<p>
		Atenciosamente, <br /> Equipe SisPMPI
	</p>
</div>