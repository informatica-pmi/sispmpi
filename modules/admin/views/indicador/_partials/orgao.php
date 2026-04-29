<?php

use app\models\Status;
use app\components\helpers\Universal;

?>

<div class="indicador-orgao">
<h6 class="mb-4">Órgãos e Entidades</h6>
    <div class="row">
        <?php foreach ($resultAllOrgaos as $index => $resultOrgao) : ?>
            <div class="col-12 col-md-4 mb-3 mb-lg-0">
                <div class="p-3 border bg-white card-result">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="text-white d-flex align-items-center mb-3 mb-lg-0 p-3 p-lg-0 justify-content-center icon mr-0 mr-lg-3 c-shadow c-radius">
                            <?php
                            if ($index == Status::PLANO_PUBLICADO) :
                                $icon = 'far fa-check-circle fa-lg';
                            elseif ($index == Status::PLANO_N_INICIADO) :
                                $icon = 'far fa-times-circle fa-lg';
                            else :
                                $icon = 'far fa-clock fa-lg';
                            endif;
                            ?>
                            <?= Universal::icon($icon) ?>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center">
                                <p class="mb-0 text-center text-lg-left mb-2 mb-lg-0">
                                    <span class="text-muted small">Programas de integridade</span>
                                    <span class="font-weight-semibold d-block">
                                        <?= Status::getPlanoStatusChart($index) ?>
                                    </span>
                                </p>

                                <span class="small text-danger font-weight-semibold rounded-pill px-3 py-1 bg-red-chart-light">
                                    <?= sprintf(
                                        '%s de %s',
                                        $resultOrgao['total'],
                                        $totalCount,
                                    ) ?>
                                </span>
                            </div>

                            <div class="mt-3 pt-3 border-top">
                                <p class="font-weight-semibold small mb-2 d-flex justify-content-between align-items-center">
                                    <span>Progresso</span>
                                    <span><?= $resultOrgao['percentage'] ?>%</span>
                                </p>

                                <div class="progress" style="height: 10px;">
                                    <div
                                        class="progress-bar rounded bg-green-chart"
                                        role="progressbar"

                                        aria-valuenow="<?= $resultOrgao['percentage']?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
