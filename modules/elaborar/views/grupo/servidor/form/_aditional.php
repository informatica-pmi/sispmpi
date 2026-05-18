<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\base\Template;
use app\base\Txt;
use app\components\helpers\Universal;
use app\models\Status;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $modelsGrupoServidor app\models\GrupoServidor[] */
/* @var $modelGrupoInstituido app\modules\elaborar\GrupoInstituido */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servidor-form">
    <?php $form = ActiveForm::begin(['id' => 'form-servidor']) ?>

    <?= $form->field($modelGrupoInstituido, 'nome_numero')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelGrupoInstituido, 'data_publicacao')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => ['id' => "grupoinstituido-data_publicacao-aditional-servidor"],
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'orientation' => 'bottom auto',
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelGrupoInstituido, 'link')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="card card-outline card-secondary">
        <div class="card-body pb-0">
            <h6 class="border-bottom mb-3 pb-3">Servidores</h6>
            <table class="table table-bordered table-sm mb-3">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Coordenador</th>
                        <th scope="col">Informações pessoais</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelsGrupoServidor as $index => $modelGrupoServidor) : ?>
                        <?php
                        if (!$modelGrupoServidor->isNewRecord) :
                            echo Html::activeHiddenInput($modelGrupoServidor, "[{$index}]servidor_id");
                        endif;
                        ?>

                        <tr>
                            <td class="not-margin text-center">
                                <?= $form->field($modelGrupoServidor, "[{$index}]status", [
                                    'template' => Template::$t['radioNotLabel']
                                ])->radioButtonGroup(Status::getPadrao(), [
                                    'class' => 'btn-group-sm',
                                    'itemOptions' => ['labelOptions' => ['class' => 'btn btn-outline-info']]
                                ]) ?>
                            </td>

                            <td class="not-margin text-center">
                                <?= $form->field($modelGrupoServidor, "[{$index}]coordenador", [
                                    'template' => Template::$t['radioNotLabel']
                                ])->radioButtonGroup(Status::getResposta(), [
                                    'class' => 'btn-group-sm',
                                    'itemOptions' => ['labelOptions' => ['class' => 'btn btn-outline-warning']]
                                ]) ?>
                            </td>

                            <td>
                                <?=
                                    "{$modelGrupoServidor->servidor->nome} -
                                    {$modelGrupoServidor->servidor->masp_matricula} /
                                    {$modelGrupoServidor->servidor->unidadeAdministrativa->nome}"
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper_aditional',
                'widgetBody' => '.container-items-aditional',
                'widgetItem' => '.item-aditional',
                'limit' => 99,
                'min' => 0,
                'insertButton' => '.add-item-aditional',
                'deleteButton' => '.remove-item-aditional',
                'model' => $modelsServidor[0],
                'formId' => 'form-servidor',
                'formFields' => [
                    'nome',
                    'masp_matricula',
                    'unidade_administrativa_id',
                ],
            ]); ?>

            <div class="card">
                <div class="card-body container-items-aditional">
                    <?php foreach ($modelsServidor as $index => $modelServidor) : ?>
                        <div class="item-aditional card">
                            <div class="card-body">
                                <?php
                                if (!$modelServidor->isNewRecord) {
                                    echo Html::activeHiddenInput($modelServidor, "[{$index}]id");
                                }
                                ?>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <?= $form->field($modelServidor, "[{$index}]nome")
                                            ->textInput(['maxlength' => true])
                                        ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?= $form->field($modelServidor, "[{$index}]masp_matricula")
                                            ->textInput(['maxlength' => true])
                                        ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?= $form->field($modelServidor, "[{$index}]coordenador", [
                                            'template' => Template::$t['radio']
                                        ])->radioButtonGroup(
                                            Status::getResposta(),
                                            [
                                                'class' => 'w-100',
                                                'itemOptions' => [
                                                    'labelOptions' => ['class' => 'btn btn-outline-secondary w-50']
                                                ],
                                            ]
                                        ) ?>
                                    </div>
                                    <div class="col-sm-5">
                                        <?= $form->field($modelServidor, "[{$index}]unidade_administrativa_id")
                                            ->dropDownList($optionsUnidadeAdministrativa, [
                                                'prompt' => Txt::$t['prompt']
                                            ])
                                        ?>
                                    </div>
                                </div>

                                <?= Html::button(
                                    Universal::icon('fas fa-times') . ' Remover',
                                    ['class' => 'remove-item-aditional btn btn-link text-danger p-0']
                                ) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer">
                    <?= Html::button(
                        Universal::icon('fas fa-plus') . ' Incluir novo membro',
                        ['class' => 'add-item-aditional btn btn-link text-success p-0']
                    ) ?>
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>

<?php
$js = <<<JS
    $('.add-item-aditional').click(function () {
        $('.dynamicform_wrapper_aditional').on('afterInsert', function(e, item) {
            let coordenadorField = $(item).find('input[type="hidden"][name*="coordenador"]');
            coordenadorField.attr('value', 0);

            let coordenadorRadio = $(item).find('div[id$="coordenador"]');
            let coordenadorRadioId = coordenadorRadio.attr('id');

            $('#' + coordenadorRadioId + ' label').removeClass('active');

            $('#' + coordenadorRadioId + ' input').removeAttr('checked');

            $('#' + coordenadorRadioId + ' label:nth-child(2)').addClass('active');

            console.log(coordenadorRadioId);
        });
    });
JS;
$this->registerJs($js);
