<?php

use app\modules\admin\models\Orgao;

?>

<h6 class="my-4">Tipos de Órgão</h6>
<div class="row">
    <?php foreach ($resultTipoOrgaos as $index => $resultTipoOrgao) : ?>
        <div class="col-12 col-md-4 col-xl mb-3 mb-xl-0">
            <div class="bg-white border card-result">
                <div class="text-center p-3">
                    <p class="mb-0 font-weight-semibold">
                        <?= Orgao::getTipo($resultTipoOrgao['orgao_tipo']) ?>
                    </p>
                    <span class="text-muted small">Programas de integridade</span>
                </div>

                <div class="result-progress px-3 pb-1">
                    <p class="font-weight-semibold small mb-2">Progresso</p>

                    <div class="progress mb-2" style="height: 5px;">
                        <div
                            class="progress-bar rounded bg-green-chart"
                            role="progressbar"
                            aria-valuenow="<?= $resultTipoOrgao['publicados']?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>

                    <div class="progress mb-2" style="height: 5px;">
                        <div
                            class="progress-bar rounded bg-yellow-chart"
                            role="progressbar"
                            aria-valuenow="<?= $resultTipoOrgao['elaboracao']?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>

                    <div class="progress mb-2" style="height: 5px;">
                        <div
                            class="progress-bar rounded bg-red-chart"
                            role="progressbar"
                            aria-valuenow="<?= $resultTipoOrgao['naoIniciados']?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>
                </div>

                <div class="result-legend">
                    <div class="border-top px-3 py-2 small text-muted d-flex align-items-center justify-content-between">
                        <p class="mb-0 d-flex align-items-center">
                            <span class="circle bg-green-chart d-inline-block rounded-circle mr-2"></span>
                            Publicados
                        </p>
                        <span><?= $resultTipoOrgao['publicados'] ?>%</span>
                    </div>
                    <div class="border-top px-3 py-2 small text-muted d-flex align-items-center justify-content-between">
                        <p class="mb-0 d-flex align-items-center">
                            <span class="circle bg-yellow-chart d-inline-block rounded-circle mr-2"></span>
                            Em elaboração
                        </p>
                        <span><?= $resultTipoOrgao['elaboracao'] ?>%</span>
                    </div>
                    <div class="border-top px-3 pt-2 pb-3 small text-muted d-flex align-items-center justify-content-between">
                        <p class="mb-0 d-flex align-items-center">
                            <span class="circle bg-red-chart d-inline-block rounded-circle mr-2"></span>
                            Não iniciados
                        </p>
                        <span><?= $resultTipoOrgao['naoIniciados'] ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
