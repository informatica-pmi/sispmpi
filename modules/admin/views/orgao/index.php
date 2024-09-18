<?php

use yii\grid\GridView;
use app\components\helpers\Button;
use app\components\helpers\Universal;
use app\models\Status;
use app\modules\admin\models\Orgao;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\pesquisa\OrgaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Órgãos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgao-index">
    <p>
        <?= Button::generate(
            'orgao-cadastrar',
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

            'nome',
            'sigla',
            [
                'attribute' => 'tipo',
                'filter' => Orgao::getTipo(),
                'value' => function ($model) {
                    return Orgao::getTipo($model->tipo);
                }
            ],
            [
                'attribute' => 'status',
                'contentOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'filter' => Status::getPadrao(),
                'value' => function ($model) {
                    return Universal::convertStatus($model->status);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 15%'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Button::generate('orgao-visualizar', null, 'far fa-eye', '', $url, true, 'Visualizar');
                    },
                    'update' => function ($url, $model, $key) {
                        return Button::generate('orgao-editar', null, 'far fa-edit', '', $url, true, 'Editar');
                    },
                    'delete' => function ($url, $model, $key) {
                        return Button::generate(
                            'orgao-apagar',
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
            ],
        ],
    ]); ?>


</div>
