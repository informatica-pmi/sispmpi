<?php

use app\components\helpers\Audit;
use app\models\Historico;

/* @var $this yii\web\View */
/* @var $modelsAcao app\models\Acao[] */
/* @var $historicos */
/* @var $modelServidor app\models\Servidor */
/* @var $modelAcaoExecucao app\modules\executar\models\AcaoExecucao */
/* @var $withFilter */
/* @var $dataInicio */
/* @var $dataFim */
?>

<div class="pdf-historico-index">
    <?php foreach ($modelsAcao as $acao) : ?>
        <div class="card">
            <div class="card-header">
                Ação <?= $acao->numero ?>: <?= $acao->titulo ?>
            </div>
            <div class="card-body">
                <?php $historicosAcao = $withFilter ?
                    $acao->getPeriodHistoricos($dataInicio, $dataFim)->all() :
                    $acao->historicos;
                ?>

                <?php $verifyChangesExists = empty($historicosAcao) &&
                    empty($historicos['Servidor'][$acao->id]) &&
                    empty($historicos['AcaoExecucao'][$acao->id])
                ?>

                <?php if ($verifyChangesExists) : ?>
                    <p>Nenhuma alteração realizada.</p>
                <?php else : ?>
                    <?php array_map(function (Historico $historicoAcao) use ($acao) { ?>
                        <?= Audit::prepareData(
                            $historicoAcao,
                            $acao->getAttributeLabel($historicoAcao->campo),
                            $acao->behaviors
                        ) ?>
                    <?php }, $historicosAcao) ?>

                    <?php if (!empty($historicos['Servidor'][$acao->id])) : ?>
                        <h3 class="mb-0">Servidores</h3>

                        <?= $this->render('servidor', [
                            'historicoServidors' => $historicos['Servidor'][$acao->id],
                            'modelServidor' => $modelServidor,
                            'acaoId' => $acao->id,
                            'countServidors' => count($historicos['Servidor'][$acao->id]),
                            'oldRegister' => '',
                            'closeRegister' => '',
                        ]) ?>
                    <?php endif ?>

                    <?php if (!empty($historicos['AcaoExecucao'][$acao->id])) : ?>
                        <h3>Execucação da ação</h3>

                        <?= $this->render('execucao', [
                            'historicoAcaoExecucaos' => $historicos['AcaoExecucao'][$acao->id],
                            'modelAcaoExecucao' => $modelAcaoExecucao
                        ]) ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
