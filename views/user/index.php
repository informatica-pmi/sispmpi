<?php

use app\models\User;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\components\helpers\Button;

/* @var $this yii\web\View */
/* @var $searchModel app\models\pesquisa\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuários';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

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
            [
                'attribute' => 'orgao_id',
                'visible' => User::getPerfil() != User::PERFIL_AUDITOR,
                'value' => function ($model) {
                    return $model->orgao->nome;
                }
            ],
            [
                'attribute' => 'perfil',
                'filter' => User::getPerfil() === User::PERFIL_AUDITOR ?
                    User::getFilterAuditor() :
                    User::getFilterCge(),
                'value' => function ($model) {
                    return implode(", ", ArrayHelper::getColumn($model->authAssignments, 'item_name'));
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 15%'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Button::generate(
                            'usuario-visualizar',
                            $model,
                            'far fa-eye',
                            '',
                            $url,
                            true,
                            'Visualizar'
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Button::generate(
                            'usuario-editar',
                            $model,
                            'far fa-edit',
                            '',
                            in_array($model->authAssignment->item_name, User::getFilterAuditor()) ?
                                ['/user-outro/update', 'id' => $model->id] :
                                $url,
                            true,
                            'Editar'
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Button::generate(
                            'usuario-apagar',
                            $model,
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
