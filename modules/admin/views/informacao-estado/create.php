<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\InformacaoEstado */

$this->title = 'Cadastrar';
$this->params['breadcrumbs'][] = ['label' => 'Informações do Estado', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacao-estado-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
