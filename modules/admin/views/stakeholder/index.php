<?php

use yii\grid\GridView;
use app\components\helpers\Button;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\pesquisa\StakeholderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Stakeholder */

$this->title = 'Stakeholders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stakeholder-not-placeholder">

    <?php if (Universal::temPermissao('stakeholder-cadastrar')) : ?>
        <div class="card card-outline card-secondary">
            <div class="card-body">
                <h6 class="border-bottom pb-3 mb-3">
                    <?= $model->id ?
                        Universal::icon('far fa-edit') . ' Atualizar' :
                        Universal::icon('fas fa-plus') . ' Novo'
                    ?>
                </h6>

                <?= $this->render('_form', [
                    'model' => $model
                ]); ?>
            </div>
        </div>
    <?php endif; ?>

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
                'contentOptions' => ['width' => '30%'],
                'value' => function ($model) {
                    return Universal::valueField($model->orgao, 'nome');
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' => ['width' => '15%'],
                'buttons' => [
                    'update' => function ($url, $model, $key) use ($page) {
                        return Button::generate(
                            'stakeholder-editar',
                            null,
                            'far fa-edit',
                            '',
                            ['index', 'page' => $page, 'id' => $model->id],
                            true,
                            'Editar'
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Button::generate(
                            'stakeholder-apagar',
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
