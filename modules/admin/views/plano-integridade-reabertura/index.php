<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\pesquisa\OrgaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reabertura';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plano-integridade-reabertura-index">
    <p>
        Esta funcionalidade exibe uma lista completa com os programas de integridade finalizado, assim pode ser
        realizado a reabertura do módulo 01 do plano escolhido.
    </p>

    <p class="text-danger mb-3 pb-3 border-bottom">
        <strong>
            AO REALIZAR ESTE PROCEDIMENTO TODOS OS DADOS DO PASSO 07 - PUBLICAÇÃO SERÁ APAGADO, O MESMO DEVERÁ SER PREENCHIDO NOVAMENTE.
        </strong>
    </p>

    <section class="list">
        <small class="text-muted">
            <?= sprintf('%s item(s) encontrados', $dataProvider->getTotalCount()) ?>
        </small>

        <?php array_map(function (PlanoIntegridade $plano) { ?>
            <div class="card">
                <div class="card-body">
                    <div class="card-text">
                        <?= sprintf(
                            '<h6>%s (%s)</h6> %s - Versão %s',
                            $plano->orgao->nome,
                            $plano->orgao->sigla,
                            $plano->edicao,
                            number_format($plano->versao, 2, '.', '')
                        ) ?>
                    </div>
                </div>
                <div class="card-footer">
                    <?= Html::a(
                        Universal::icon('fas fa-redo-alt') . ' Reabrir módulo 01',
                        ['create', 'planoId' => $plano->id],
                        [
                            'class' => 'btn btn-primary',
                            'data-confirm' => 'Este procedimento é irreversivel, deseja continuar?'
                        ]
                    ) ?>
                </div>
            </div>
        <?php }, $dataProvider->getModels()) ?>
    </section>

    <?= LinkPager::widget([
        'pagination' => $dataProvider->getPagination(),
    ]) ?>
</div>
