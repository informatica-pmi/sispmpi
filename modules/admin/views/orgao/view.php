<?php

use kartik\detail\DetailView;
use yii\bootstrap4\LinkPager;
use app\components\helpers\Button;
use app\components\helpers\Universal;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orgao */
/* @var $modelsContabil app\modules\admin\models\OrgaoContabil[] */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Órgãos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orgao-view">

    <p>
        <?= Button::generate(
            'informacao-estado-editar',
            null,
            'far fa-edit',
            'Editar',
            ['update', 'id' => $model->id],
            false,
            '',
            'primary'
        ) ?>

        <?= Button::generate(
            'informacao-estado-apagar',
            null,
            'fas fa-trash',
            'Apagar',
            ['delete', 'id' => $model->id],
            false,
            '',
            'danger',
            true
        ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'hAlign' => 'left',
        'attributes' => [
            'id',
            'nome',
            'sigla',
            [
                'attribute' => 'tipo',
                'value' => Orgao::getTipo($model->tipo),
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => Universal::convertStatus($model->status),
            ]
        ],
    ]) ?>

    <div class="row mt-3">
        <?php foreach ($modelsContabil as $index => $modelContabil) : ?>
            <div class="col-sm-6">
                <div class="info-box shadow-none border">
                    <span class="info-box-icon bg-gray elevation-1"><?= Universal::icon('far fa-calendar-alt') ?></span>

                    <div class="info-box-content">
                        <h3 class="info-box-text font-weight-normal"><?= $modelContabil->ano ?></h3>

                        <span class="info-box-text text-muted">
                            <?= $modelContabil->getAttributeLabel('orcamento') ?>:
                            <?= Universal::convertCurrency($modelContabil->orcamento) ?>
                        </span>

                        <span class="info-box-text text-muted">
                            <?= $modelContabil->getAttributeLabel('quantitativo_pessoal') ?>:
                            <?= $modelContabil->quantitativo_pessoal ?>
                        </span>

                        <span class="info-box-text text-muted">
                            Orçamento total do estado:
                            <?= array_key_exists($modelContabil->ano, $infsEstado) ?
                                Universal::convertCurrency($infsEstado[$modelContabil->ano]) :
                                'Não cadastrado'
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pages
    ]) ?>
</div>






</div>
