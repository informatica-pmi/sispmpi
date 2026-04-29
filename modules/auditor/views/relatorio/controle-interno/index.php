<?php

use app\components\helpers\Universal;
use app\models\PlanoIntegridadeRecomendacao;

/* @var $modelsPlanoIntegridadeRecomendacao app\models\PlanoIntegridadeRecomendacao */
/* @var $userOrgaoNome */
?>
<div class="controle-interno-index">
    <div class="pb-3 mb-3">
        <header class="mb-2">
            <h6><?= $userOrgaoNome ?></h6>
        </header>
    </div>

    <?php array_map(function (PlanoIntegridadeRecomendacao $planoIntegridadeRecomendacao) { ?>
        <div class="card">
            <div class="card-body">
                <p class="my-1 font-weight-bold">Recomendação</p>

                <p>
                    <?= $planoIntegridadeRecomendacao->recomendacao ?>
                </p>

                <div class="border-bottom"></div>

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
        </div>
    <?php }, $modelsPlanoIntegridadeRecomendacao) ?>
</div>
