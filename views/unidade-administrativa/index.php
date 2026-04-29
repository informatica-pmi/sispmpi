<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\components\helpers\Button;
use app\components\helpers\Universal;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\pesquisa\UnidadeAdministrativaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\UnidadeAdministrativa */
/* @var $responseInfEstado app\modules\admin\models\InformacaoEstado */
/* @var $responseOrgaoContabil app\modules\admin\models\Orgao */

$conditionPerfil = ArrayHelper::isIn(User::getPerfil(), [User::PERFIL_TI, User::PERFIL_ADMINISTRADOR]);
$this->title = 'Unidades Administrativas';
$this->params['breadcrumbs'][] = $this->title;?>

<div class="unidade-administrativa-not-placeholder">

    <?php if ($responseInfEstado && $responseOrgaoContabil) : ?>
        <div class="card-group mb-3">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <h6 class="border-bottom pb-3 mb-3">
                        <?= Universal::icon('fas fa-info') ?> Informações do Estado
                    </h6>

                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseInfEstado->getAttributeLabel('ano'),
                                $responseInfEstado->ano
                            ) ?>
                        </small>
                    </p>
                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseInfEstado->getAttributeLabel('orcamento'),
                                Universal::convertCurrency($responseInfEstado->orcamento)
                            ) ?>
                        </small>
                    </p>
                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseInfEstado->getAttributeLabel('quantitativo_pessoal'),
                                $responseInfEstado->quantitativo_pessoal
                            ) ?>
                        </small>
                    </p>
                </div>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <h6 class="border-bottom pb-3 mb-3">
                        <?= Universal::icon('fas fa-info') ?> Informações do Órgão
                    </h6>

                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s - %s',
                                $responseOrgaoContabil->orgao->getAttributeLabel('nome'),
                                $responseOrgaoContabil->orgao->nome,
                                $responseOrgaoContabil->orgao->sigla
                            ) ?>
                        </small>
                    </p>
                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseOrgaoContabil->getAttributeLabel('ano'),
                                $responseOrgaoContabil->ano
                            ) ?>
                        </small>
                    </p>
                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseOrgaoContabil->getAttributeLabel('orcamento'),
                                Universal::convertCurrency($responseOrgaoContabil->orcamento)
                            ) ?>
                        </small>
                    </p>
                    <p class="card-text m-0">
                        <small class="text-muted">
                            <?= sprintf(
                                '%s: %s',
                                $responseOrgaoContabil->getAttributeLabel('quantitativo_pessoal'),
                                $responseOrgaoContabil->quantitativo_pessoal
                            ) ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <?php if (Universal::temPermissao('unidade-administrativa-cadastrar')) : ?>
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
                'visible' => $conditionPerfil,
                'value' => function ($model) {
                    return $model->orgao->nome;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visible' => !$conditionPerfil,
                'buttons' => [
                    'update' => function ($url, $model, $key) use ($page) {
                        return Button::generate(
                            'unidade-administrativa-editar-orgao',
                            $model,
                            'far fa-edit',
                            '',
                            ['index', 'id' => $model->id, 'page' => $page],
                            true,
                            'Editar'
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Button::generate(
                            'unidade-administrativa-apagar-orgao',
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
