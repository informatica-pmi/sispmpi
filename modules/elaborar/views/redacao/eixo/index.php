<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\elaborar\models\pesquisa\EixoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="eixo-index">

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['class' => 'grid-view table-responsive'],
            'tableOptions' => ['class' => 'table table-striped table-bordered mb-0'],
            'headerRowOptions' => ['class' => 'bg-info'],
            'filterRowOptions' => ['class' => 'bg-info disabled'],
            'pager' => ['class' => 'yii\bootstrap4\LinkPager'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'titulo',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'contentOptions' => ['width' => '20%'],
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Universal::temPermissao('preencher-elaboracao', $model->planoIntegridade) ?
                                Html::a(
                                    Universal::icon('far fa-edit'),
                                    [
                                        '@elaborar/redacao/default/create',
                                        'planoId' => $model->plano_integridade_id,
                                        'key' => 'eixo',
                                        'updateId' => $model->id
                                    ],
                                    [
                                        'class' => 'btn btn-primary btn-sm',
                                        'data-pjax' => 0,
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'title' => 'Editar',
                                    ]
                                ) : null;
                        },
                        'delete' => function ($url, $model, $key) {
                            return Universal::temPermissao('preencher-elaboracao', $model->planoIntegridade) ?
                                Html::a(
                                    Universal::icon('fas fa-trash') . ' Apagar',
                                    ['@elaborar/redacao/eixo/delete', 'eixoId' => $model->id],
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'toggle' => 'tooltip',
                                            'placement' => 'top',
                                            'title' => 'Apagar',
                                        ],
                                    ],
                                ) : null;
                        }
                    ]
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>

</div>
