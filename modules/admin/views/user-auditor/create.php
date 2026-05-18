<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Cadastrar';
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
