<?php

use app\components\helpers\Universal;
use app\modules\avaliar\models\Promover;

/* @var $modelsPromover app\modules\avaliar\models\PromoverIntegridade[] */
/* @var $filterDates */
?>

<div class="pdf-promover-integridade-index">
    <header class="mb-3">
        <p>
            <u><strong>Filtros</strong></u>:
            <?= !empty($filterDates) ? sprintf(
                'de %s até %s.',
                Universal::convertDate($filterDates['inicio']),
                Universal::convertDate($filterDates['termino'])
            ) : 'Nenhum filtro definido.' ?>
        </p>
    </header>

    <?php array_map(function (Promover $promover) { ?>
        <div class="card">
            <div class="card-header">
                <?= $promover->acao_desenvolvida ?>
            </div>

            <div class="card-body">
                <div class="data-content">
                    <p class="my-1">
                        <?= sprintf(
                            '%s: %s',
                            $promover->getAttributeLabel('data'),
                            Universal::convertDate($promover->data)
                        ) ?>
                    </p>

                    <p>
                        <?= sprintf(
                            '%s: %s',
                            $promover->getAttributeLabel('horas_trabalho'),
                            $promover->horas_trabalho
                        ) ?>
                    </p>
                </div>
            </div>
        </div>
    <?php }, $modelsPromover) ?>
</div>
