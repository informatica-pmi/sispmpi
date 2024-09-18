<?php

use yii\bootstrap4\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\helpers\Universal;
use app\modules\avaliar\models\Promover;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\avaliar\models\pesquisa\PromoverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promoção da integridade';
$this->params['breadcrumbs'][] = ['label' => 'Avaliação do programa de integridade', 'url' => ['@avaliar']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promover-integridade-not-placeholder">

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between">
                <span class="flex-grow-1 mb-1 mb-sm-0">Adicionar novo registro</span>

                <?= Html::button(Universal::icon('fas fa-plus-circle') . ' Cadastrar', [
                    'class' => 'btn btn-success',
                    'value' => Url::to(['create']),
                    'id' => 'modalPromoverIntegridadeCreate',
                    'data-show' => 'modal-promover-integridade'
                ]) ?>
            </div>
        </div>
    </div>

    <section class="search">
        <hr>

        <h6><?= Universal::icon('fas fa-filter') ?> Filtros</h6>

        <p class="card-text"><i>É necessário o preenchimento das duas datas para o bom funcionamento do filtro.</i></p>

        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </section>

    <section class="list">
        <small class="text-muted"><?= $dataProvider->getTotalCount() ?> item(s) econtrados</small>

        <div class="card card-outline card-primary">
            <div class="card-body pb-1">
                <?php array_map(function (Promover $promover) { ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text mb-3 pb-3 border-bottom"><?= $promover->acao_desenvolvida ?></p>

                            <small class="text-muted">
                                <?= sprintf(
                                    '%s - %s hora(s) trabalhada(s)',
                                    Universal::convertDate($promover->data),
                                    $promover->horas_trabalho
                                ) ?>
                            </small>
                        </div>
                        <div class="card-footer">
                            <?= Html::button(Universal::icon('far fa-edit') . ' Editar', [
                                'class' => 'btn btn-primary',
                                'value' => Url::to(['update', 'id' => $promover->id]),
                                'id' => "modalPromoverIntegridadeUpdate-{$promover->id}",
                                'data-show' => 'modal-promover-integridade'
                            ]) ?>
                        </div>
                    </div>
                <?php }, $dataProvider->getModels()) ?>
            </div>
        </div>
    </section>

    <?= LinkPager::widget([
        'pagination' => $pagination,
    ]) ?>

</div>

<?php

Universal::modal('Promoção da integridade', 'modal-promover-integridade', 'modal-xl');
