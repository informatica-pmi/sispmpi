<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\AlterarPerfil $model
 * @var app\modules\admin\models\Orgao[] $optionsOrgao
 * @var string $to
 */

$this->title = 'Alterar Perfil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alterar-perfil-update">
    <p>Você está prestes a alterar o seu perfil para <?= $to ?>. Para concluir a mudança, por favor, selecione um órgão.</p>

    <?= $this->render('_form', [
        'model' => $model,
        'optionsOrgao' => $optionsOrgao
    ]) ?>
</div>
