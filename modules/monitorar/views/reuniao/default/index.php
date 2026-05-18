<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\helpers\Universal;
use app\modules\monitorar\models\Reuniao;
use yii\bootstrap4\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitorar\models\pesquisa\ReuniaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reuniões';
$this->params['breadcrumbs'][] = [
    'label' => 'Monitoramento das ações do programa de integridade',
    'url' => ['@monitorar']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reuniao-not-placeholder">

    <div class="card card-outline card-secondary">
        <div class="card-body">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between">
                <span class="flex-grow-1 mb-1 mb-sm-0">Adicionar novo registro</span>
                <?= Html::button(Universal::icon('fas fa-plus-circle') . ' Cadastrar', [
                    'class' => 'btn btn-success',
                    'value' => Url::to(['create']),
                    'id' => 'modalReuniao',
                    'data-show' => 'modal-reuniao'
                ]) ?>
            </div>
        </div>
    </div>

    <section class="search">
        <hr>

        <h6><?= Universal::icon('fas fa-filter') ?> Filtros</h6>

        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </section>

    <section class="list">
        <small class="text-muted"><?= $dataProvider->getTotalCount() ?> item(s) econtrados</small>

        <div class="card card-outline card-primary">
            <div class="card-body pb-1">
                <?php array_map(function (Reuniao $reuniao) { ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text mb-1">
                                <span><?= $reuniao->nome ?></span>
                            </p>

                            <small class="text-muted"><?= Universal::convertDate($reuniao->data) ?></small>
                        </div>
                        <div class="card-footer">
                            <?= Html::a(
                                Universal::icon('far fa-file-pdf') . ' Gerar PDF',
                                ['@monitorar/reuniao/pdf/index', 'reuniaoId' => $reuniao->id],
                                [
                                    'class' => 'btn btn-danger',
                                    'target' => '_blank',
                                    'rel' => 'noopener noreferrer'
                                ]
                            ) ?>
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

<?php Universal::modal('Reunião', 'modal-reuniao', 'modal-xl'); ?>

<?php

$js = <<<JS
    $('#modal-reuniao').on('hidden.bs.modal', function() {
        tinymce.remove();
    });
JS;

$this->registerJs($js);
