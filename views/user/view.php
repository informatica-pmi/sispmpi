<?php

use app\models\User;
use kartik\detail\DetailView;
use app\components\helpers\Button;
use app\components\helpers\Universal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->nome;

if (Universal::temPermissao('usuario-listar')) :
    $this->params['breadcrumbs'][] =  ['label' => 'Usuários', 'url' => ['index']];
endif;

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <p>
        <?= Button::generate(
            'usuario-editar',
            $model,
            'far fa-edit',
            'Editar',
            [
                User::getPerfil() === User::PERFIL_AUDITOR && User::getIdentidade('id') != $model->id ?
                    '/user-outro/update' :
                    'update',
                'id' => $model->id
            ],
            false,
            '',
            'primary'
        ) ?>

        <?= Button::generate(
            'usuario-apagar',
            $model,
            'fas fa-trash',
            'Apagar',
            ['delete', 'id' => $model->id],
            false,
            '',
            'danger',
            true
        ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'mb-3'],
        'hAlign' => 'left',
        'hideIfEmpty' => true,
        'attributes' => [
            'id',
            'nome',
            'masp',
            'login',
            'cargo',
            'email:email',
            'telefone',
            [
                'attribute' => 'perfil',
                'format' => 'html',
                'value' => function ($form, $widget) {
                    $model = $widget->model;
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
                'attribute' => 'orgao_id',
                'value' => $model->orgao->nome,
            ],
            [
                'attribute' => 'unidade_administrativa_id',
                'value' => Universal::valueField($model->unidadeAdministrativa, 'nome'),
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => Universal::convertStatus($model->status),
            ],
        ],
    ]) ?>

</div>
