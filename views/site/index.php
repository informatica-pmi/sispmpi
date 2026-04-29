<?php

use app\models\User;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $alertShowAuditor */
/* @var $alertShowAuditorUnidade */
/* @var $alertShowMonitoramento */

$this->title = 'Módulos';

$hasElaboracao = Universal::temPermissao('modulo-elaboracao');
$hasExecucao = Universal::temPermissao('modulo-execucao');
$hasMonitoramento = Universal::temPermissao('modulo-monitoramento');
$hasAvaliacao = Universal::temPermissao('modulo-avaliacao');
?>

<div class="site-index">

    <div class="card-group mb-3">
        <div class="<?= $hasElaboracao ? 'card' : 'card card-disabled' ?>">
            <a href=<?= $hasElaboracao ? Yii::getAlias('@web/elaborar') : '#' ?> class="text-body">
                <p class="text-center module-title bg-orange">
                    <?= Universal::icon('fas fa-scroll text-white') ?>
                    <span class="font-weight-light text-white">01</span>
                </p>
                <div class="card-body">
                    <h5 class="card-title font-weight-semi-bold mb-2">
                        Elaboração do programa e do plano de integridade
                    </h5>
                    <p class="card-text text-justify">
                        Módulo de estruturação do programa e do plano de integridade da organização. Auxilia o processo
                        de formulação do programa de integridade, fornecendo ferramentas para sua redação. Apoia o
                        desenho do plano de integridade em eixos, subeixos e ações a serem implementadas.
                    </p>
                </div>
            </a>
        </div>

        <div class="<?= $hasExecucao ? 'card' : 'card card-disabled' ?>">
            <a href=<?= $hasExecucao ? Yii::getAlias('@web/executar') : '#' ?> class="text-body">
                <p class="text-center module-title bg-maroon">
                    <?= Universal::icon('fas fa-play-circle') ?>
                    <span class="font-weight-light">02</span>
                </p>
                <div class="card-body">
                    <h5 class="card-title font-weight-semi-bold mb-2">Execução do plano de integridade</h5>
                    <p class="card-text text-justify">
                        Módulo para auxiliar o processo de execução do plano de integridade da organização, cuja
                        alimentação de informações é realizada pelas unidades administrativas tecnicamente competentes
                        para a execução de cada ação.
                    </p>
                </div>
            </a>
        </div>

        <div class="<?= $hasMonitoramento ? 'card' : 'card card-disabled' ?>">
            <a href=<?= $hasMonitoramento ? Yii::getAlias('@web/monitorar') : '#' ?> class="text-body">
                <p class="text-center module-title bg-indigo">
                    <?= Universal::icon('far fa-calendar-check') ?>
                    <span class="font-weight-light">03</span>
                </p>
                <div class="card-body">
                    <h5 class="card-title font-weight-semi-bold mb-2">Monitoramento do programa de integridade</h5>
                    <p class="card-text text-justify">
                        Módulo para auxiliar o processo de monitoramento do programa de integridade da organização,
                        realizado pelos membros da comissão de integridade. Possibilita a visualização de gráficos, o
                        registro de atas e a inserção de recomendações às unidades executoras das ações do Plano de
                        Integridade organizacional, dentre outras ferramentas.
                    </p>
                </div>
            </a>
        </div>

        <div class="<?= $hasAvaliacao ? 'card' : 'card card-disabled' ?>">
            <a href=<?= $hasAvaliacao ? Yii::getAlias('@web/avaliar') : '#' ?> class="text-body">
                <p class="text-center module-title bg-teal">
                    <?= Universal::icon('fas fa-spell-check') ?>
                    <span class="font-weight-light">04</span>
                </p>
                <div class="card-body">
                    <h5 class="card-title font-weight-semi-bold mb-2">Avaliação do programa de integridade</h5>
                    <p class="card-text">
                        Módulo para auxiliar o processo de avaliação do programa de integridade pela unidade de
                        controle interno da organização. Possibilita a visualização de gráficos e relatórios, o
                        registro de ações realizadas para a promoção da integridade, a inserção de recomendações às
                        unidades executoras e a comissão de integridade, dentre outras ferramentas.
                    </p>
                </div>
            </a>
        </div>
    </div>

    <?php if ($alertShowAuditor) : ?>
        <div class="card card-outline card-danger">
            <div class="card-body small text-muted">
                <h6 class="text-danger border-bottom pb-3 mb-3">
                    <?= Universal::icon('fas fa-exclamation-triangle') ?> Alerta
                </h6>

                <p class="mb-1">
                    O plano de integridade da instituição <?= User::getIdentidade('orgao', 'nome') ?> foi concluído
                    com sucesso. Acesse o módulo 1 para obter à versão final do plano de integridade e do plano de ação.
                </p>

                <p class="mb-1">
                    Para que seja dado início a execução das ações do plano de integridade, o responsável pelo
                    monitoramento deve encaminhar a relação dos usuários perfil execução, para que se proceda ao
                    cadastramento dos mesmos no sistema.
                </p>

                <p class="mb-1">Não deixe de cadastrar os usuários do módulo 2, ou seja, o perfil executor.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($alertShowMonitoramento) : ?>
        <div class="card card-outline card-danger">
            <div class="card-body small text-muted">
                <h6 class="text-danger border-bottom pb-3 mb-3">
                    <?= Universal::icon('fas fa-exclamation-triangle') ?> Alerta
                </h6>

                <p class="mb-1">
                    O plano de integridade da instituição <?= User::getIdentidade('orgao', 'nome') ?> foi concluído com
                    sucesso.
                </p>

                <p class="mb-1">A próxima etapa é a execução das ações estabelecidas no plano.</p>

                <p class="mb-1">
                    Para que seja dado início a execução das ações do plano de integridade, procure as unidades
                    administrativas responsáveis pelas ações (registradas no plano de ação) e confirme os dados
                    dos servidores que ficarão responsáveis pela execução de cada ação.
                </p>

                <p class="mb-1">
                    Anote o nome, Masp/matrícula, telefone institucional, e-mail institucional, cargo e unidade
                    administrativa e repasse essas informações para o Auditor, para que o mesmo proceda ao cadastro
                    dos responsáveis pela execução de cada ação.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($alertShowAuditorUnidade) : ?>
        <div class="card card-outline card-danger">
            <div class="card-body small text-muted">
                <h6 class="text-danger border-bottom pb-3 mb-3">
                    <?= Universal::icon('fas fa-exclamation-triangle') ?> Alerta
                </h6>

                <p class="mb-1">
                    É recomendável que o cadastro das unidades administrativas da organização seja realizado antes do
                    cadastro dos usuários do sistema.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
