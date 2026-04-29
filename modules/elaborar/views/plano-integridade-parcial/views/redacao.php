<?php

use app\components\helpers\Universal;
use app\models\Acao;
use app\modules\elaborar\models\Eixo;
use app\modules\elaborar\models\Subeixo;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $modelsEixo app\modules\elaborar\models\Eixo[] */
?>
<div class="redacao-view">
    <h2>Plano de integridade</h2>

    <?php array_map(function (Eixo $eixo) { ?>
        <h3><?= $eixo->titulo ?></h3>

        <p><?= Universal::stripTags($eixo->descricao) ?></p>

        <?php if ($eixo->acaos) : ?>
            <?php array_map(function (Acao $acao) { ?>
                <?php if (is_null($acao->subeixo_id)) : ?>
                    <h4>Ação: <?= $acao->titulo ?></h4>

                    <p>
                        <?= sprintf(
                            '<strong>%s</strong>: %s',
                            $acao->getAttributeLabel('numero'),
                            $acao->numero
                        ); ?>
                    </p>

                    <p>
                        <?= sprintf(
                            '<strong>%s</strong>%s',
                            $acao->getAttributeLabel('descricao'),
                            Universal::stripTags($acao->descricao)
                        ); ?>
                    </p>

                    <p><strong><?= $acao->getAttributeLabel('unidade_executora') ?></strong></p>

                    <p><?= $acao->unidadeExecutora->nome ?></p>

                    <?php if ($acao->acaoUnidadeApoios) : ?>
                        <p>
                            <strong><?= $acao->getAttributeLabel('unidadeApoioIds') ?></strong>
                        </p>

                        <p>
                            <?= implode(
                                ", ",
                                ArrayHelper::getColumn($acao->acaoUnidadeApoios, 'unidadeAdministrativa.nome')
                            ) ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($acao->objetivo)) : ?>
                        <p>
                            <?= sprintf(
                                '<strong>%s</strong>%s',
                                $acao->getAttributeLabel('objetivo'),
                                Universal::stripTags($acao->objetivo)
                            ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($acao->beneficio_instituicao)) : ?>
                        <p>
                            <?= sprintf(
                                '<strong>%s</strong>%s',
                                $acao->getAttributeLabel('beneficio_instituicao'),
                                Universal::stripTags($acao->beneficio_instituicao)
                            ); ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php }, $eixo->acaos) ?>
        <?php endif; ?>

        <?php if ($eixo->subeixos) : ?>
            <?php array_map(function (Subeixo $subeixo) { ?>
                <h4>Subeixo: <?= $subeixo->titulo ?></h4>

                <p><?= Universal::stripTags($subeixo->descricao) ?></p>

                <?php if ($subeixo->acaos) : ?>
                    <div class="child">
                        <?php array_map(function (Acao $acao) { ?>
                            <h4>Ação: <?= $acao->titulo ?></h4>

                            <p>
                                <?= sprintf(
                                    '<strong>%s</strong>: %s',
                                    $acao->getAttributeLabel('numero'),
                                    $acao->numero
                                ); ?>
                            </p>

                            <p>
                                <?= sprintf(
                                    '<strong>%s</strong>%s',
                                    $acao->getAttributeLabel('descricao'),
                                    Universal::stripTags($acao->descricao)
                                ); ?>
                            </p>

                            <p><strong><?= $acao->getAttributeLabel('unidade_executora') ?></strong></p>

                            <p><?= $acao->unidadeExecutora->nome ?></p>

                            <?php if ($acao->acaoUnidadeApoios) : ?>
                                <p>
                                    <strong><?= $acao->getAttributeLabel('unidadeApoioIds') ?></strong>
                                </p>

                                <p>
                                    <?= implode(
                                        ", ",
                                        ArrayHelper::getColumn($acao->acaoUnidadeApoios, 'unidadeAdministrativa.nome')
                                    ) ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($acao->objetivo)) : ?>
                                <p>
                                    <?= sprintf(
                                        '<strong>%s</strong>%s',
                                        $acao->getAttributeLabel('objetivo'),
                                        Universal::stripTags($acao->objetivo)
                                    ); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($acao->beneficio_instituicao)) : ?>
                                <p>
                                    <?= sprintf(
                                        '<strong>%s</strong>%s',
                                        $acao->getAttributeLabel('beneficio_instituicao'),
                                        Universal::stripTags($acao->beneficio_instituicao)
                                    ); ?>
                                </p>
                            <?php endif; ?>
                        <?php }, $subeixo->acaos) ?>
                    </div>
                <?php endif; ?>
            <?php }, $eixo->subeixos) ?>
        <?php endif; ?>
    <?php }, $modelsEixo) ?>

</div>
