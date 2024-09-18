<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use app\base\Template;
use app\base\Txt;
use app\components\helpers\Universal;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelsServidor app\models\Servidor[] */
/* @var $optionsUnidadeAdministrativa app\models\UnidadeAdministrativa[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servidor-form">

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 99,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsServidor[0],
        'formId' => 'form-grupo',
        'formFields' => [
            'nome',
            'masp_matricula',
            'unidade_administrativa_id',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body container-items">
            <h6 class="border-bottom pb-3 mb-3">
                <?= Universal::icon('far fa-user') ?> Servidores
            </h6>

            <?php foreach ($modelsServidor as $index => $modelServidor) : ?>
                <div class="item card">
                    <div class="card-body">
                        <?php
                        if (!$modelServidor->isNewRecord) :
                            echo Html::activeHiddenInput($modelServidor, "[{$index}]id");
                        endif;
                        ?>

                        <div class="row">
                            <div class="col-sm-3">
                                <?= $form->field($modelServidor, "[{$index}]nome")->textInput(['maxlength' => true]) ?>
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
                                        ]
                                    ]
                                ) ?>
                            </div>
                            <div class="col-sm-5">
                                <?= $form->field($modelServidor, "[{$index}]unidade_administrativa_id")->dropDownList(
                                    $optionsUnidadeAdministrativa,
                                    ['prompt' => Txt::$t['prompt']]
                                ) ?>
                            </div>
                        </div>

                        <?= Html::button(
                            Universal::icon('fas fa-times') . ' Remover',
                            ['class' => 'remove-item btn btn-link text-danger p-0']
                        ) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <?= Html::button(
                Universal::icon('fas fa-plus') . ' Adicionar',
                ['class' => 'add-item btn btn-link text-success p-0']
            ) ?>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>
</div>

<?php
$js = <<<JS
    $('.add-item').click(function () {
        $('.dynamicform_wrapper').on('afterInsert', function(e, item) {
            let coordenadorField = $(item).find('input[type="hidden"][name*="coordenador"]');
            coordenadorField.attr('value', 0);

            let coordenadorRadio = $(item).find('div[id$="coordenador"]');
            let coordenadorRadioId = coordenadorRadio.attr('id');
            $('#' + coordenadorRadioId + ' label').removeClass('active');
            $('#' + coordenadorRadioId + ' label:nth-child(2)').addClass('active');

        });
    });
JS;
$this->registerJs($js);
