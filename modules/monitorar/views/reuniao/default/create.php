<?php

/* @var $this yii\web\View */
/* @var $modelReuniao app\modules\monitorar\models\Reuniao */
/* @var $modelsServidor app\models\Servidor[] */

$this->title = 'Cadastrar Reunião';
$this->params['breadcrumbs'][] = ['label' => 'Reuniaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reuniao-create">

    <?= $this->render('_form', [
        'modelReuniao' => $modelReuniao,
        'modelsServidor' => $modelsServidor,
    ]) ?>

</div>
