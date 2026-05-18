<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orgao */
/* @var $modelsContabil app\modules\admin\models\OrgaoContabil[] */

$this->title = 'Cadastrar';
$this->params['breadcrumbs'][] = ['label' => 'Órgãos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgao-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsContabil' => $modelsContabil
    ]) ?>

</div>
