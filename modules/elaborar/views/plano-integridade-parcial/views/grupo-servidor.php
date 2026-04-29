<?php

use yii\helpers\ArrayHelper;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelGrupo app\modules\elaborar\models\Grupo */

$firstGrupoServidors = $modelGrupo->firstGrupoServidors;
$alterationGrupoServidors = $modelGrupo->withoutFirstGrupoServidors;

$orders = array_values(array_unique(ArrayHelper::getColumn($modelGrupo->withoutFirstGrupoServidors, 'order')));
?>
<div class="grupo-servidor-view">
    <h2>Servidores</h2>

    <?= implode(", <br/ >", ArrayHelper::getColumn($firstGrupoServidors, function ($element) {
        $servidor = $element['coordenador'] === Status::STATUS_SIM ?
            "<strong>{$element['servidor']['nome']}</strong>" :
            "<span>{$element['servidor']['nome']}</span>";

        $servidor .= ", {$element['servidor']['masp_matricula']} -
            {$element['servidor']['unidadeAdministrativa']['nome']}";

        return $servidor;
    })); ?>

    <?php if ($alterationGrupoServidors) : ?>
        <?php foreach ($orders as $order) : ?>
            <h3>Alteração na composição da comissão de integridade #<?= $order ?></h3>

            <?php $prepareGrupoServidor = ArrayHelper::getColumn(
                $alterationGrupoServidors,
                function ($element) use ($order) {
                    if ($element['order'] == $order) {
                        if ($element['status'] == Status::STATUS_INATIVO) {
                            $servidor = "<s>{$element['servidor']['nome']}</s>";
                        } elseif ($element['coordenador'] === Status::STATUS_SIM) {
                            $servidor = "<strong>{$element['servidor']['nome']}</strong>";
                        } else {
                            $servidor = "<span>{$element['servidor']['nome']}</span>";
                        }

                        $servidor .= ", {$element['servidor']['masp_matricula']} -
                            {$element['servidor']['unidadeAdministrativa']['nome']}";

                        return $servidor;
                    }
                }
            ) ?>

            <?= implode(', <br/ >', array_filter($prepareGrupoServidor)) ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
