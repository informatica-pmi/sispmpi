<?php

/* @var $this yii\web\View */
/* @var $modelValidacao app\models\Validacao */
/* @var $optionsStakeholder app\models\Stakeholder[] */

$this->title = 'Validação geral';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="validacao-update">

    <?= $this->render('_form', [
        'modelValidacao' => $modelValidacao,
        'optionsStakeholder' => $optionsStakeholder,
    ]) ?>

</div>
