<?php

use app\models\Acao;
use app\models\Status;

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

                    <?php if (isset($acao->acaoExecucao)) : ?>
                        <?= $this->render('execucao', [
                            'modelAcaoExecucao' => $acao->acaoExecucao
                        ]) ?>
                    <?php endif; ?>

                    <?php if (isset($acao->acaoMonitoramento)) : ?>
                        <?= $this->render('monitoramento', [
                            'modelAcaoMonitoramento' => $acao->acaoMonitoramento
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php }, $modelsAcao); ?>
</div>
