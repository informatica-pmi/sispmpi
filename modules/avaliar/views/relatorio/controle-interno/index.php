<?php

use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Status;
use app\modules\monitorar\models\AcaoMonitoramento;
use yii\helpers\ArrayHelper;

/* @var $modelsPlanoIntegridadeRecomendacao app\models\PlanoIntegridadeRecomendacao[] */
/* @var $modelsPlanoPromover app\modules\avaliar\models\Promover[] */
/* @var $modelsAcao app\models\Acao[] */
/* @var $withFilter */
/* @var $dataInicio */
/* @var $dataFim */
/* @var $userOrgaoNome */
?>
<div class="pdf-controle-interno-index">
    <div class="pb-3 mb-3">
        <header class="mb-2">
            <h6><?= $userOrgaoNome ?></h6>
        </header>
    </div>

    <div class="card">
        <div class="card-header">
            Recomendações do controle interno
        </div>

        <?php if (empty($modelsAcao)) : ?>
            <div class="card-body">
                <p>Nenhuma ação encontrada</p>
            </div>
        <?php else : ?>
            <?= $this->render('avaliacao', [
                'modelsAcao' => $modelsAcao,
                'withFilter' => $withFilter,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim
            ]) ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">Recomendações gerais à comissão de integridade</div>
        <div class="card-body">
            <?php if (empty($modelsPlanoIntegridadeRecomendacao)) : ?>
                <p>Sem recomendações</p>
            <?php else : ?>
                <?php $arrayKeyLast = array_key_last($modelsPlanoIntegridadeRecomendacao); ?>

                <?php foreach ($modelsPlanoIntegridadeRecomendacao as $index => $planoIntegridadeRecomendacao) : ?>
                    <div class="border-bottom <?= $arrayKeyLast === $index ? 'last-item' : ''?>">
                        <p class="my-1 font-weight-bold">Recomendação</p>

                        <p class="mb-3">
                            <?= $planoIntegridadeRecomendacao->recomendacao ?>
                        </p>

                        <p class="my-1 font-weight-bold">Resposta</p>

                        <p>
                            <?= Universal::valueField(
                                $planoIntegridadeRecomendacao->resposta,
                                null,
                                true,
                                'Aguardando resposta.'
                            ) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Ações de promoção da integridade realizadas pela unidade de controle interno da organização
        </div>
        <div class="card-body">
            <?php if (empty($modelsPlanoPromover)) : ?>
                <p>Nenhuma ação encontrada</p>
            <?php else : ?>
                    <?= $this->render('promover', [
                        'modelsPlanoPromover' => $modelsPlanoPromover
                    ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
