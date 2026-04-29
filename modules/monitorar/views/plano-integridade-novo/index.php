<?php

use yii\helpers\Html;
use app\components\helpers\Universal;
use app\models\PlanoIntegridadeNovo;

/* @var $this yii\web\View */
?>

<div class="solicitacao-index">
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title font-weight-semi-bold mb-2">
                    Elaborar nova versão do atual programa de integridade
                </h5>

                <p class="card-text text-justify">
                    Ao selecionar esta opção, o módulo 1 do SisPMPI será habilitado e permitirá que a comissão de
                    integridade altere o programa de integridade atual.
                </p>

                <p class="card-text text-justify">
                    Todos os registros já realizados serão mantidos.
                </p>
            </div>
            <div class="card-footer">
                <?= Html::a(
                    Universal::icon('fas fa-spell-check') . ' Solicitar',
                    ['create', 'tipoId' => PlanoIntegridadeNovo::TIPO_NOVA_VERSAO],
                    [
                        'class' => 'btn btn-primary',
                        'data-confirm' => 'Deseja confirmar a solicitação de atualização do programa de integridade?',
                    ]
                ) ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title font-weight-semi-bold mb-2">
                    Elaborar nova edição do programa de integridade
                </h5>

                <p class="card-text text-justify">
                    Ao selecionar esta opção, o Módulo 1 do SisPMPI será habilitado e permitirá que a comissão de
                    integridade desenvolva um novo programa de integridade.
                </p>

                <p class="card-text text-justify">
                    Nenhum registro já realizado será mantido.
                </p>
            </div>
            <div class="card-footer">
                <?= Html::a(
                    Universal::icon('fas fa-spell-check') . ' Solicitar',
                    ['create', 'tipoId' => PlanoIntegridadeNovo::TIPO_NOVA_EDICAO],
                    [
                        'class' => 'btn btn-primary',
                        'data-confirm' => 'Deseja confirmar a solicitação de atualização do programa de integridade?',
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>
