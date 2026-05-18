<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $optionsOrgao app\models\Orgao[] */

$this->title = 'Atualizar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['/user/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        'optionsOrgao' => $optionsOrgao,
    ]) ?>

</div>
