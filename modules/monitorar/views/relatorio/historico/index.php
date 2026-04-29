<?php

ini_set("pcre.backtrack_limit", "5000000");

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
    <?php foreach ($modelsAcao as $modelAcao) : ?>
        <div class="card">
            <div class="card-header">
                Ação <?= $modelAcao->numero ?>: <?= $modelAcao->titulo ?>
            </div>
            <div class="card-body">
                <?php $historicosAcao = $withFilter ?
                    $modelAcao->getPeriodHistoricos($dataInicio, $dataFim)->all() :
                    $modelAcao->historicos;
                ?>

                <?php $verifyChangesExists = empty($historicosAcao) &&
                    empty($historicos['Servidor'][$modelAcao->id]) &&
                    empty($historicos['AcaoExecucao'][$modelAcao->id])
                ?>

                <?php if ($verifyChangesExists) : ?>
                    <p>Nenhuma alteração realizada.</p>
                <?php else : ?>
                    <?php array_map(function (Historico $historicoAcao) use ($modelAcao) { ?>
                        <?= Audit::prepareData(
                            $historicoAcao,
                            $modelAcao->getAttributeLabel($historicoAcao->campo),
                            $modelAcao->behaviors
                        ) ?>
                    <?php }, $historicosAcao) ?>

                    <?php if (!empty($historicos['Servidor'][$modelAcao->id])) : ?>
                        <h3 class="mb-0">Servidores</h3>

                        <?= $this->render('servidor', [
                            'historicoServidors' => $historicos['Servidor'][$modelAcao->id],
                            'modelServidor' => $modelServidor,
                            'acaoId' => $modelAcao->id,
                            'countServidors' => count($historicos['Servidor'][$modelAcao->id]),
                            'oldRegister' => '',
                            'closeRegister' => '',
                        ]) ?>
                    <?php endif ?>

                    <?php if (!empty($historicos['AcaoExecucao'][$modelAcao->id])) : ?>
                        <h3>Execucação da ação</h3>

                        <?= $this->render('execucao', [
                            'historicoAcaoExecucaos' => $historicos['AcaoExecucao'][$modelAcao->id],
                            'modelAcaoExecucao' => $modelAcaoExecucao
                        ]) ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
