<?php

/* @var $modelPlanoIntegridadeNovo app\models\PlanoIntegridadeNovo */
/* @var $isNewVersion */

$this->title = $isNewVersion ? 'Revisão do programa de integridade' : 'Nova edição do programa de integridade';
$this->params['breadcrumbs'][] = ['label' => 'Avaliação do programa de integridade', 'url' => ['@avaliar']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plano-integridade-novo-update">

    <?= $this->render('_form', [
        'modelPlanoIntegridadeNovo' => $modelPlanoIntegridadeNovo,
        'isNewVersion' => $isNewVersion
    ]) ?>

</div>
