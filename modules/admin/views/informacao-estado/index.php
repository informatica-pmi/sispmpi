<?php

use yii\grid\GridView;
use app\components\helpers\Button;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\pesquisa\InformacaoEstadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informações do Estado';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacao-estado-index">

    <p>
        <?= Button::generate(
            'informacao-estado-cadastrar',
            null,
            'fas fa-plus-circle',
            'Cadastrar',
            ['create'],
            false,
            '',
            'success'
        ) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view table-responsive'],
        'headerRowOptions' => ['class' => 'bg-info'],
        'filterRowOptions' => ['class' => 'bg-info disabled'],
        'pager' => ['class' => 'yii\bootstrap4\LinkPager'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ano',
            [
                'attribute' => 'orcamento',
                'value' => function ($model) {
                    return Universal::convertCurrency($model->orcamento);
                }
            ],
            'quantitativo_pessoal',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Button::generate(
                            'informacao-estado-visualizar',
                            null,
                            'far fa-eye',
                            '',
                            $url,
                            true,
                            'Visualizar'
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Button::generate(
                            'informacao-estado-editar',
                            null,
                            'far fa-edit',
                            '',
                            $url,
                            true,
                            'Editar'
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Button::generate(
                            'informacao-estado-apagar',
                            null,
                            'fas fa-trash',
                            'Apagar',
                            $url,
                            false,
                            '',
                            'danger btn-sm',
                            true
                        );
                    }
                ]
            ]
        ]
    ]); ?>


</div>
