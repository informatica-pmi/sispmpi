<?php

use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;
use app\components\helpers\Semaphore;
use app\models\Acao;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $userOrgaoNome app\modules\admin\models\Orgao */
/* @var $modelsAcao app\models\Acao[] */
/* @var $dateCompletion */
/* @var $lastDateModified */
?>
<div class="pdf-executar-index">
    <div class="border-bottom pb-3 mb-3">
        <header class="mb-2">
            <h5><?= $userOrgaoNome ?></h5>
        </header>

        <div>
            <p class="small">
                Versão do plano de integridade: 1
            </p>

            <p class="small">
                Concluída em: <?= $dateCompletion ?>
            </p>

            <p class="small">
                Última revisão em: <?= $lastDateModified ?>
            </p>
        </div>
    </div>

    <?php array_map(function (Acao $acao) { ?>
        <div class="card">
            <div class="card-header">
                Ação <?= $acao->numero ?>: <?= $acao->titulo ?>
            </div>

            <div class="card-body">
                <div class="data-content">
                    <p class="my-1">Eixo: <?= $acao->eixo->titulo ?></p>

                    <p class="my-1">
                        Sub-eixo: <?= Universal::valueField($acao->subeixo, 'titulo', true) ?>
                    </p>

                    <p class="my-1">Unidade Adm. Responsável: <?= $acao->unidadeExecutora->nome ?></p>

                    <div class="card">
                        <div class="card-body">
                            <p class="font-weight-bold my-1"><u>Outras informações</u></p>

                            <p class="my-1">Classificação: <?= $acao->classificacao ?></p>

                            <p class="my-1">
                                Tipo:
                                <?= implode(
                                    ", ",
                                    ArrayHelper::getColumn($acao->acaoTipos, 'tipo.nome')
                                ) ?>
                            </p>

                            <p class="my-1">Status: <?= Status::getAcaoStatus($acao->status) ?></p>

                            <p class="my-1">Semáforo:</p>

                            <div class="dot <?= Semaphore::generate($acao) ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }, $modelsAcao); ?>
</div>
