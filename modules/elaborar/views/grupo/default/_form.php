<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelGrupo app\models\Grupo */
/* @var $modelGrupoInstituido app\models\GrupoInstituido */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $prepareGrupoServidor[] */
/* @var $ordersGrupoServidorUpdate[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="grupo-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-grupo'
    ]); ?>

    <?= $this->render('../instituido/form/_create', [
        'form' => $form,
        'modelGrupoInstituido' => $modelGrupoInstituido,
    ]) ?>

    <?php if ($modelGrupo->isNewRecord) : ?>
        <?= $this->render('../servidor/form/_create', [
            'form' => $form,
            'modelsServidor' => $modelsServidor,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        ]) ?>
    <?php endif; ?>

    <?php if (!$modelGrupo->isNewRecord) : ?>
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            id="pills-preview-tab"
                            data-toggle="pill"
                            href="#pills-preview"
                            role="tab"
                            aria-controls="pills-preview"
                            aria-selected="true"
                        >
                            Preview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            id="pills-update-tab"
                            data-toggle="pill"
                            href="#pills-update"
                            role="tab"
                            aria-controls="pills-update"
                            aria-selected="true"
                        >
                            Editar
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div
                    class="tab-pane fade show active"
                    id="pills-preview"
                    role="tabpanel"
                    aria-labelledby="pills-preview-tab"
                >
                    <?= implode(", ", $prepareGrupoServidor) ?>
                </div>
                <div class="tab-pane fade" id="pills-update" role="tabpanel" aria-labelledby="pills-update-tab">
                    <?= $this->render('../servidor/form/_create', [
                        'form' => $form,
                        'modelsServidor' => $modelsServidor,
                        'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
                    ]) ?>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-6">
                <?= $this->render('../instituido/view', [
                    'modelsGrupoInstituido' => $modelsGrupoInstituido
                ]) ?>
            </div>

            <div class="col-sm-6">
                <?= $this->render('../servidor/view', [
                    'orders' => $ordersGrupoServidorUpdate,
                    'modelGrupo' => $modelGrupo,
                    'modelsGrupoServidor' => $modelGrupo->withoutFirstGrupoServidors,
                    'grupoId' => $modelGrupo->id
                ]) ?>
            </div>
        </div>

    <?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?php if (!$modelGrupo->isNewRecord) : ?>
            <div class="dropdown d-inline">
                <button
                    class="btn btn-outline-info dropdown-toggle"
                    type="button" id="dropdownMenuAlteracoes"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Alterações na instituição da comissão de integridade
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuAlteracoes">
                    <?= Html::button(Universal::icon('far fa-clock') . ' Prorrogação de prazo', [
                        'value' => Url::to(['@elaborar/grupo/instituido/create', 'grupoId' => $modelGrupo->id]),
                        'class' => 'dropdown-item',
                        'id' => 'modalGrupoInstitudio',
                        'data-show' => 'modal-grupo-instituido'
                    ]) ?>

                    <?= Html::button(
                        Universal::icon('far fa-user') . ' Alteração na composição da comissão de integridade',
                        [
                            'value' => Url::to(['@elaborar/grupo/servidor/create', 'grupoId' => $modelGrupo->id]),
                            'class' => 'dropdown-item',
                            'id' => 'modalServidors',
                            'data-show' => 'modal-servidors'
                        ]
                    ) ?>
                </div>
            </div>
        <?php endif; ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

Universal::modal('Prorrogação de prazo', 'modal-grupo-instituido', 'modal-xl');
Universal::modal('Prorrogação de prazo', 'modal-grupo-instituido-update', 'modal-xl');
Universal::modal('Alteração na composição da comissão de integridade', 'modal-servidors', 'modal-xl');
Universal::modal('Alteração na composição da comissão de integridade', 'modal-servidors-update', 'modal-xl');
