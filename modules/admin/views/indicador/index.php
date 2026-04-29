<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Status;
use app\modules\admin\models\Orgao;
use app\components\helpers\Universal;
use kartik\form\ActiveForm;

$this->title = 'Indicadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicador-not-placeholder">
    <header class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 border-bottom pb-4">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <?= Html::img(
                '@web/images/logo-sidebar.png',
                ['alt' => 'Logo', 'class' => 'img-fluid mr-2', 'style' => 'width:20%']
            ) ?>
            <h3 class="font-weight-semibold mb-0">Indicadores</h3>
        </div>
        <?= Html::a(
            Universal::icon('fas fa-angle-double-left mr-2') . 'Voltar',
            Yii::$app->homeUrl,
            ['class' => 'text-muted small']
        )?>
    </header>

    <?= $this->render('_partials/orgao', ['resultAllOrgaos' => $resultAllOrgaos, 'totalCount' => $totalCount]) ?>

    <?= $this->render('_partials/tipo-orgao', ['resultTipoOrgaos' => $resultTipoOrgaos]) ?>

    <div class="row">
        <div class="col-12 col-lg-6 col-xl-5">
            <?= $this->render(
                '_partials/orcamento',
                ['informacaoEstado' => $informacaoEstado, 'resultOrcamento' => $resultOrcamento]
            ) ?>
        </div>
        <div class="col-12 col-lg-6 col-xl-5">
            <?= $this->render(
                '_partials/servidor',
                ['informacaoEstado' => $informacaoEstado, 'resultQuantitativoPessoal' => $resultQuantitativoPessoal]
            ) ?>
        </div>
        <div class="col-12 col-lg-12 d-flex flex-column col-xl-2">
            <h6 class="my-4">Tempo médio para elaboração do programa de integridade</h6>
            <div class="bg-white border card-result flex-grow-1 p-4 d-flex">
                <div class="text-white bg-purple-chart c-radius c-shadow flex-grow-1 d-flex flex-column align-items-center justify-content-center p-3">
                    <span style="opacity: 0.5"><?= Universal::icon('far fa-calendar-alt fa-3x mb-3') ?></span>
                    <h3 class="font-weight-semibold mb-3 text-center integer-number">
                        <span><?= Universal::formatInteger($resultTempoMedio['tempo_medio']) ?></span> dias
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <?= $this->render('_partials/outros', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>
        </div>
    </div>
</div>

