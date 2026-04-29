<?php

/* @var $this yii\web\View */
/* @var $model app\models\InformacaoGeral */

$this->title = 'Atualizar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Informações do Estado', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="informacao-estado-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
