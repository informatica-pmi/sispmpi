<?php

/* @var $this yii\web\View */
/* @var $modelEixo app\models\Eixo */
/* @var $searchModelEixo app\modules\elaborar\models\pesquisa\EixoSearch */
/* @var $dataProviderEixo yii\data\ActiveDataProvider */
/* @var $modelSubeixo app\models\Subeixo */
/* @var $searchModelSubeixo app\modules\elaborar\models\pesquisa\SubeixoSearch */
/* @var $dataProviderSubeixo yii\data\ActiveDataProvider */
/* @var $modelAcao app\models\Acao */
/* @var $searchModelAcao app\models\pesquisa\AcaoSearch */
/* @var $dataProviderAcao yii\data\ActiveDataProvider */
/* @var $planoId */
/* @var $prepareTabs */
/* @var $optionsEixo app\modules\elaborar\Eixo */

$this->title = 'Redação';
$this->params['breadcrumbs'][] = ['label' => 'Elaboração do programa de integridade', 'url' => ['@elaborar']];
$this->params['breadcrumbs'][] = 'Cadastrar';
?>
<div class="diagnostico-create">

    <?= $this->render('_form', [
        'planoId' => $planoId,
        'modelEixo' => $modelEixo,
        'searchModelEixo' => $searchModelEixo,
        'dataProviderEixo' => $dataProviderEixo,
        'modelSubeixo' => $modelSubeixo,
        'searchModelSubeixo' => $searchModelSubeixo,
        'dataProviderSubeixo' => $dataProviderSubeixo,
        'modelAcao' => $modelAcao,
        'searchModelAcao' => $searchModelAcao,
        'dataProviderAcao' => $dataProviderAcao,
        'prepareTabs' => $prepareTabs,
        'optionsEixo' => $optionsEixo
    ]) ?>

</div>
