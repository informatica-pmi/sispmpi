<?php

/* @var $this yii\web\View */
/* @var $modelsPlanoIntegridadeRecomendacao app\models\PlanoIntegridadeRecomendacao */

$this->title = 'Recomendações gerais';
$this->params['breadcrumbs'][] = [
    'label' => 'Monitoramento das ações do programa de integridade',
    'url' => ['@monitorar']
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="plano-integridade-recomendacao-update">

    <div class="card card-outline card-secondary">
        <div class="card-body pb-0">
            <?= $this->render('_form', [
                'modelsPlanoIntegridadeRecomendacao' => $modelsPlanoIntegridadeRecomendacao,
            ]) ?>
        </div>
    </div>

</div>
