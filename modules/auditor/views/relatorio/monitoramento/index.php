<?php

use app\models\Acao;
use app\models\AcaoMonitoramentoRecomendacao;
use app\models\Status;
use app\modules\monitorar\models\AcaoMonitoramento;

/* @var $this yii\web\View */
/* @var $userOrgaoNome app\modules\admin\models\Orgao */
/* @var $modelsAcao app\models\Acao[] */
?>

<div class="pdf-monitoramento-index">
    <div class="border-bottom pb-3 mb-3">
        <header class="mb-2">
            <h5><?= $userOrgaoNome ?></h5>
        </header>
    </div>

    <?php array_map(function (Acao $acao) { ?>
        <?php
        $modelAcaoExecucao = $acao->acaoExecucao;
        $modelAcaoMonitoramento = $acao->acaoMonitoramento;
        $modelsAcaoAvaliacaoRecomendacao = $acao->acaoAvaliacaoRecomendacaos;

        $isEmptyModelAcaoMonitoramento = empty($modelAcaoMonitoramento);
        $modelAcaoMonitoramento = $isEmptyModelAcaoMonitoramento ? new AcaoMonitoramento() : $modelAcaoMonitoramento;
        ?>

        <div class="card">
            <div class="card-header">
                Ação <?= $acao->numero ?>: <?= $acao->titulo ?>
            </div>
            <div class="card-body">
                <div class="data-content">
                    <p>
                        <span class="font-weight-bold">Status:</span>
                        <?= Status::getAcaoStatus($acao->status) ?>
                    </p>

                    <?php if (!empty($modelAcaoExecucao)) : ?>
                        <?= $this->render('execucao', [
                            'modelAcaoExecucao' => $modelAcaoExecucao
                        ]) ?>
                    <?php endif; ?>

                    <?= $this->render('monitoramento', [
                        'modelAcaoMonitoramento' => $modelAcaoMonitoramento,
                        'modelsAcaoMonitoramentoRecomendacao' => $isEmptyModelAcaoMonitoramento ?
                            $modelAcaoMonitoramento->acaoMonitoramentoRecomendacaos :
                            [new AcaoMonitoramentoRecomendacao()]
                    ]) ?>

                    <?= $this->render('controle-interno', [
                        'modelsAcaoAvaliacaoRecomendacao' => $modelsAcaoAvaliacaoRecomendacao
                    ]) ?>
                </div>
            </div>
        </div>
    <?php }, $modelsAcao); ?>
</div>
