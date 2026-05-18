<?php

use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $unidadeApoioNomes */
/* @var $servidorResponsavelNome */
/* @var $servidorEnvolvidoNomes */
?>

<div class="responsabilidade-view-only-reading">
    <div class="card border-primary bw-3 mb-0">
        <div class="card-body">
            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('unidade_executora') ?>:
                </span>

                <?= $modelAcao->unidadeExecutora->nome ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    <?= $modelAcao->getAttributeLabel('unidadeApoioIds') ?>:
                </span>

                <?= Universal::valueField(implode(', ', $unidadeApoioNomes), null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    Servidor ou servidora responsável pela ação:
                </span>

                <?= Universal::valueField($servidorResponsavelNome, null, true) ?>
            </p>

            <p class="mb-1">
                <span class="font-weight-semi-bold">
                    Servidores envovidos na ação:
                </span>

                <?= Universal::valueField(implode(', ', $servidorEnvolvidoNomes), null, true) ?>
            </p>
        </div>
    </div>
</div>
