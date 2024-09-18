<?php

/* @var $this yii\web\View */
/* @var $model app\admin\models\Monitoramento */
/* @var $optionsOrgao app\admin\models\Orgao[] */

$this->title = 'Monitoramento';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monitoramento-update">
    <?= $this->render('_form', [
        'model' => $model,
        'optionsOrgao' => $optionsOrgao
    ]) ?>
</div>
