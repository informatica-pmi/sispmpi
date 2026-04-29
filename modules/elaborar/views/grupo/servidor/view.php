<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\helpers\Universal;
use app\models\Status;
use app\modules\elaborar\models\GrupoInstituido;

/* @var $this yii\web\View */
/* @var $modelsGrupoServidor app\models\GrupoServidor[] */
/* @var $modelsGrupoServidor app\modules\elaborar\models\GrupoServidor[] */
/* @var $orders[] */
/* @var $grupoId */
?>
<div class="servidor-view">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <h6>Alteração na composição da comissão de integridade</h6>
            <hr>
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <?php foreach ($orders as $index => $order) : ?>
                            <li class="nav-item">
                                <?php $title = $index + 1; ?>
                                <?= Html::a(
                                    "#{$title}",
                                    "#servidors-{$index}",
                                    [
                                        'class' => $index == 0 ? 'nav-link active' : 'nav-link',
                                        'id' => "servidors-{$index}-tab",
                                        'data-toggle' => 'tab',
                                        'role' => 'tab',
                                        'aria-controls' => "servidors-{$index}",
                                        'aria-selected' => $index == 0 ? 'true' : 'false'
                                    ]
                                ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <?php foreach ($orders as $index => $order) : ?>
                        <div
                            class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>"
                            id="servidors-<?= $index ?>"
                            role="tabpanel"
                            aria-labelledby="servidors-<?= $index ?>-tab"
                        >
                            <div class="data-content">
                                <?php
                                $grupoInstituido = $modelGrupo->getGrupoInstituidos()
                                    ->where(['order' => $order, 'tipo' => GrupoInstituido::TIPO_SERVIDOR])
                                    ->one();

                                $prepareGrupoServidor = ArrayHelper::getColumn(
                                    $modelsGrupoServidor,
                                    function ($modelGrupoServidor) use ($order) {
                                        if ($modelGrupoServidor['order'] == $order) {
                                            if ($modelGrupoServidor['status'] === Status::STATUS_INATIVO) :
                                                return "<del>{$modelGrupoServidor['servidor']['nome']}</del>";
                                            elseif ($modelGrupoServidor['coordenador'] === Status::STATUS_SIM) :
                                                return "<strong>{$modelGrupoServidor['servidor']['nome']}</strong>";
                                            endif;

                                            return "<span>{$modelGrupoServidor['servidor']['nome']}</span>";
                                        }
                                    }
                                ); ?>

                                <?php if ($grupoInstituido) : ?>
                                    <?php $grupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL_SERVIDOR; ?>
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

                                    <?= Html::a(
                                        $grupoInstituido->getAttributeLabel('link'),
                                        $grupoInstituido->link,
                                        [
                                            'class' => 'btn btn-primary btn-sm mb-3',
                                            'target' => 'blank',
                                            'rel' => 'noopener noreferrer'
                                        ]
                                    ) ?>
                                <?php endif; ?>

                                <div class="card">
                                    <div class="card-body">
                                        <?= implode(', ', array_filter($prepareGrupoServidor)) ?>
                                    </div>
                                </div>

                                <hr>

                                <?= Html::button(
                                    Universal::icon('far fa-edit') . ' Editar',
                                    [
                                        'value' => Url::to([
                                            '@elaborar/grupo/servidor/update',
                                            'grupoId' => $grupoId,
                                            'order' => $order
                                        ]),
                                        'class' => 'btn btn-link p-0',
                                        'id' => 'modalServidorsUpdate',
                                        'data-show' => 'modal-servidors-update'
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
