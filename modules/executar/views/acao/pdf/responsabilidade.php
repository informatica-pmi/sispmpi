<?php

use app\components\helpers\Universal;
use app\models\Servidor;

/* @var $modelAcao app\models\Acao */
/* @var $servidorResponsavel */
/* @var $servidorsEnvolvido */
?>

<div class="card">
    <div class="card-header">
        Responsabilidade pela Ação
    </div>

    <div class="card-body">
        <p class="my-1">
            <span class="font-weight-bold">Unidade administrativa executora:</span>

            <?= $modelAcao->unidadeExecutora->nome ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Unidade administrativa de apoio:</span>

            <?= Universal::valueField(
                implode(", ", $modelAcao->unidadeApoioIds),
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Servidor ou servidora responsável pela ação</span>

            <?= Universal::valueField(
                $servidorResponsavel,
                null,
                true,
                'Conteúdo não informado no Plano de Integridade do órgão'
            ) ?>
        </p>

        <p class="my-1">
            <span class="font-weight-bold">Servidores envolvidos na ação:</span>

            <?php
            if (is_string($servidorsEnvolvido)) :
                echo $servidorsEnvolvido;
            else :
                array_map(function (Servidor $servidor) {
                    if ($servidor) :
                        echo "<p class='my-1'>
                            {$servidor->nome} / {$servidor->masp_matricula} - {$servidor->unidadeAdministrativa->nome}
                        </p>";
                    endif;
                }, $servidorsEnvolvido);
            endif;
            ?>
        </p>
    </div>
</div>
