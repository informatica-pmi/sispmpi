<?php

/* @var $this yii\web\View */
/* @var $modelPublicacao app\models\Publicacao */

$this->title = 'Publicação e comunicação';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Cadastrar';
?>
<div class="publicacao-create">

    <?= $this->render('_form', [
        'modelPublicacao' => $modelPublicacao,
    ]) ?>

</div>
