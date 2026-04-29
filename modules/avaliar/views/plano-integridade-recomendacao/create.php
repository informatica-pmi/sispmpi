<?php

/* @var $this yii\web\View */
/* @var $models app\models\PlanoIntegridadeRecomendacao[] */

$this->title = 'Recomendações gerais';
$this->params['breadcrumbs'][] = ['label' => 'Avaliação do programa de integridade', 'url' => ['@avaliar']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plano-integridade-create">

    <?= $this->render('_form', [
        'models' => $models
    ]) ?>

</div>
