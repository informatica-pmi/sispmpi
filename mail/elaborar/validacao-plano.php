<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="validacao-plano">
	<p>Prezado(a) <?= $params['nome'] ?>,</p>

	<p>O plano de integridade já foi validado e muito em breve o mesmo deve ser publicado.</p>

	<p>Verifique com a Alta Administração se já foi definido quem fará o monitoramento das ações.</p>

	<p>Caso tenha sido definido, proceda ao cadastro do usuário perfil <strong>Monitoramento</strong>.</p>

	<p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

	<p>
		Atenciosamente, <br /> Equipe SisPMPI
	</p>
</div>