<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $optionsOrgao app\models\Orgao[] */

$this->title = 'Cadastrar';
$this->params['breadcrumbs'][] = ['label' => 'Usuários', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        'optionsOrgao' => $optionsOrgao,
    ]) ?>

</div>
