<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orgao */
/* @var $modelsContabil app\modules\admin\models\OrgaoContabil[] */

$this->title = 'Atualizar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Órgãos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="orgao-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsContabil' => $modelsContabil
    ]) ?>

</div>
