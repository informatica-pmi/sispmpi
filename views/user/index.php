<?php

use app\models\User;
use yii\grid\GridView;
use app\components\helpers\Button;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
                'format' => 'html',
                'value' => function ($model) {
                    $items = '';
                    foreach ($model->authAssignments as $authAssignment) {
                        $items .= '<span class="d-block">';
                        if ($authAssignment->active) {
                            $items .= Html::tag('i', '', ['class' => 'fas fa-check text-success mr-2']);
                        } else {
                            $items .= Html::tag('i', '', ['class' => 'far fa-clock text-warning mr-2']);
                        }
                        $items .= $authAssignment->item_name;
                        $items .= '</span>';
                    }

                    return $items;
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
                            empty(
                                array_intersect(
                                    [User::PERFIL_ADMINISTRADOR, User::PERFIL_AUDITOR],
                                    ArrayHelper::map($model->authAssignments, 'item_name', 'item_name')
                                )
                            ) ? ['/user-outro/update', 'id' => $model->id] : $url,
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
