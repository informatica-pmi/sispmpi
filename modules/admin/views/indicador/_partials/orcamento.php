<?php

use app\models\Status;
use app\components\helpers\Universal;

?>

<div>
    <h6 class="my-4">Situação por orçamento</h6>
    <div class="bg-white border card-result">
        <?php if ($informacaoEstado) : ?>
            <div class="p-4">
                <div class="d-flex align-items-center justify-content-between flex-column-reverse flex-sm-row">
                    <h5 class="font-weight-semibold mb-0">
                        <?= Universal::icon('far fa-money-bill-alt mr-2') ?>
                        <?= Universal::convertCurrency($informacaoEstado->orcamento) ?>
                    </h5>
                    <span class="small text-danger font-weight-semibold rounded-pill px-3 py-1 bg-red-chart-light mb-3 mb-md-0">
                        <?= $informacaoEstado->ano ?>
                    </span>
                </div>
                <span class="d-block text-muted small text-center text-md-left">Orçamento Total</span>

                <p class="font-weight-semibold small mt-3 mb-2">Progresso</p>
                <div class="row">
                    <div class="col-12 col-md-4 d-flex mb-3 mb-md-0">
                        <div class="bg-green-chart text-white p-3 c-shadow c-radius">
                            <h3 class="font-weight-semibold float-number">
                                <span><?= $resultOrcamento[Status::PLANO_PUBLICADO]['percentage'] ?></span>%
                            </h3>
                            <span class="small">
                                do orçamento com programas de integridade publicados.
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex mb-3 mb-md-0">
                        <div class="bg-red-chart text-white p-3 c-shadow c-radius">
                            <h3 class="font-weight-semibold float-number">
                                <span><?= $resultOrcamento[Status::PLANO_N_INICIADO]['percentage'] ?></span>%
                            </h3>
                            <span class="small">
                                do orçamento que ainda não iniciou a formulação do programa de integridade.
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="bg-yellow-chart text-white p-3 c-shadow c-radius">
                            <h3 class="font-weight-semibold float-number">
                                <span><?= $resultOrcamento[Status::PLANO_ELABORACAO]['percentage'] ?></span>%
                            </h3>
                            <span class="small">
                                do orçamento com programas de integridade em formulação.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="result-legend small text-muted d-flex flex-column flex-md-row justify-content-center border-top p-4">
                <p class="mb-0 d-flex align-items-center">
                    <span class="circle bg-green-chart d-inline-block rounded-circle mr-2"></span>
                    Publicados
                </p>
                <p class="mb-0 d-flex align-items-center mx-0 mx-md-3">
                    <span class="circle bg-red-chart d-inline-block rounded-circle mr-2"></span>
                    Não iniciados
                </p>
                <p class="mb-0 d-flex align-items-center">
                    <span class="circle bg-yellow-chart d-inline-block rounded-circle mr-2"></span>
                    Em elaboração
                </p>
            </div>
        <?php else : ?>
            <p class="text-muted mb-0">Nenhuma informação cadastrada.</p>
        <?php endif ?>
    </div>
</div>
