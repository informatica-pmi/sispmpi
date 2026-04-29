<?php

use yii\helpers\Html;
use app\components\helpers\Universal;
use app\modules\monitorar\models\Reuniao;
use yii\bootstrap4\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitorar\models\pesquisa\ReuniaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $pagination yii\data\Pagination */

$this->title = 'Reuniões da comissão de integridade';
$this->params['breadcrumbs'][] = ['label' => 'Avaliação do programa de integridade', 'url' => ['@avaliar']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reuniao-not-placeholder">

    <div class="card card-outline card-primary">
        <div class="card-body pb-1">
            <section class="search">
                <h6><?= Universal::icon('fas fa-filter') ?> Filtros</h6>

                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </section>

            <section class="list">
                <small class="text-muted"><?= $dataProvider->getTotalCount() ?> item(s) econtrados</small>

                <?php array_map(function (Reuniao $reuniao) { ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text mb-3 pb-3 border-bottom">
                                <?= $reuniao->nome ?>
                            </p>

                            <small class="text-muted"><?= Universal::convertDate($reuniao->data) ?></small>
                        </div>
                        <div class="card-footer">
                            <?= Html::a(
                                Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                                ['@avaliar/reuniao/pdf/index', 'reuniaoId' => $reuniao->id],
                                [
                                    'class' => 'btn btn-danger',
                                    'target' => '_blank',
                                    'rel' => 'noopener noreferrer'
                                ]
                            ) ?>
                        </div>
                    </div>
                <?php }, $dataProvider->getModels()) ?>
            </section>

            <?= LinkPager::widget([
                'pagination' => $pagination,
            ]) ?>

        </div>
    </div>

</div>
