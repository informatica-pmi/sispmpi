<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="cge-publicacao">
	<p>Prezado(a) <?= $params['nome'] ?>,</p>

	<p>O plano de integridade da instituição <strong><?= $params['nomeOrgao'] ?></strong> foi concluído com sucesso.</p>

	<p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

	<p>
		Atenciosamente, <br /> Equipe SisPMPI
	</p>
</div>