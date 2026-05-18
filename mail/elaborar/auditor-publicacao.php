<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>

<div class="auditor-publicacao">
	<p>Prezado(a) <?= $params['nome'] ?>,</p>

	<p>
        O plano de integridade da instituição <strong><?= $params['nomeOrgao'] ?></strong> foi concluído com sucesso. 
        Acesse o módulo 1 para obter à versão final do plano de integridade e do plano de ação.
    </p>

	<p>
        Para que seja dado início a execução das ações do plano de integridade, o responsável pelo monitoramento deve 
        encaminhar a relação dos usuários perfil execução, para que se proceda ao cadastramento dos mesmos no sistema.
    </p>

	<p>
        Não deixe de cadastrar os usuários do módulo 2, ou seja, o perfil executor.”
    </p>

	<p>&gt;&gt;&gt; <?= Html::a('Acesse o sistema', Url::base()) ?> &lt;&lt;&lt;</p>

	<p>
		Atenciosamente, <br /> Equipe SisPMPI
	</p>
</div>