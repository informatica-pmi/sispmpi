<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\helpers\Universal;
use app\modules\elaborar\models\GrupoInstituido;

/* @var $this yii\web\View */
/* @var $modelsGrupoInstituido app\models\GrupoInstituido[] */
?>
<div class="grupo-instituido-view">

    <div class="card card-outline card-primary">
        <div class="card-body">
            <h6>Prorrogação de prazo</h6>
            <hr>
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <?php foreach ($modelsGrupoInstituido as $index => $grupoInstituido) : ?>
                            <li class="nav-item">
                                <?php $title = $index + 1; ?>
                                <?= Html::a(
                                    "#{$title}",
                                    "#prorroga-{$index}",
                                    [
                                        'class' => $index == 0 ? 'nav-link active' : 'nav-link',
                                        'id' => "prorroga-{$index}-tab",
                                        'data-toggle' => 'tab',
                                        'role' => 'tab',
                                        'aria-controls' => "prorroga-{$index}",
                                        'aria-selected' => $index == 0 ? 'true' : 'false'
                                    ]
                                ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <?php foreach ($modelsGrupoInstituido as $index => $grupoInstituido) : ?>
                        <?php $grupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL; ?>
                        <div
                            class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>"
                            id="prorroga-<?= $index ?>"
                            role="tabpanel"
                            aria-labelledby="prorroga-<?= $index ?>-tab"
                        >

                            <div class="data-content">
                                <p class="small mb-1">
                                    <span class="text-muted">
                                        <?= $grupoInstituido->getAttributeLabel('nome_numero') ?>:
                                    </span>

                                    <span class="font-weight-bold"><?= $grupoInstituido->nome_numero ?></span>
                                </p>

                                <p class="small mb-1">
                                    <span class="text-muted">
                                        <?= $grupoInstituido->getAttributeLabel('data_publicacao') ?>:
                                    </span>

                                    <span class="font-weight-bold">
                                        <?= Universal::convertDate($grupoInstituido->data_publicacao) ?>
                                    </span>
                                </p>

                                <p class="small mb-1">
                                    <span class="text-muted">
                                        <?= $grupoInstituido->getAttributeLabel('dias_conclusao') ?>:
                                    </span>

                                    <span class="font-weight-bold">
                                        <?= $grupoInstituido->dias_conclusao ?>
                                    </span>
                                </p>

                                <p class="small mb-3">
                                    <span class="text-muted">
                                        <?= $grupoInstituido->getAttributeLabel('data_prevista_conclusao') ?>:
                                    </span>

                                    <span class="font-weight-bold">
                                        <?= Universal::convertDate(
                                            $grupoInstituido->data_prevista_conclusao
                                        ) ?>
                                    </span>
                                </p>

                                <?= Html::a(
                                    $grupoInstituido->getAttributeLabel('link'),
                                    $grupoInstituido->link,
                                    [
                                        'class' => 'btn btn-primary btn-sm',
                                        'target' => 'blank',
                                        'rel' => 'noopener noreferrer'
                                    ]
                                ) ?>

                                <hr>

                                <?= Html::button(
                                    Universal::icon('far fa-edit') . ' Editar',
                                    [
                                        'value' => Url::to([
                                            '@elaborar/grupo/instituido/update',
                                            'grupoId' => $grupoInstituido->grupo_id,
                                            'order' => $grupoInstituido->order
                                        ]),
                                        'class' => 'btn btn-link p-0',
                                        'id' => 'modalGrupoInstituidoUpdate',
                                        'data-show' => 'modal-grupo-instituido-update'
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>
