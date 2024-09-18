<?php

use app\components\helpers\Universal;
use kartik\form\ActiveField;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;
use app\base\Template;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $modelPublicacao app\models\Publicacao */
/* @var $form kartik\form\ActiveForm */
?>
<div class="publicacao-form">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="card">
        <div class="card-body">
            <?= $form->field($modelPublicacao, 'evento', ['template' => Template::$t['radio']])
                ->radioButtonGroup(Status::getResposta(), [
                    'itemOptions' => [
                        'data' => [
                            'sim' => 'div-data',
                            'nao' => 'div-justificativa-evento'
                        ],
                    ],
                ])
            ?>

            <div class="div-data d-none">
                <?= $form->field($modelPublicacao, 'data_evento')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'orientation' => 'bottom auto',
                        ]
                    ]
                ]) ?>
            </div>
            <div class="div-justificativa-evento d-none">
                <?= $form->field($modelPublicacao, 'justificativa_evento', [
                    'labelOptions' => ['class' => 'is-required']
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= $form->field($modelPublicacao, 'disponibilizado', ['template' => Template::$t['radio']])
                ->radioButtonGroup(Status::getResposta(), [
                    'itemOptions' => [
                        'data' => [
                            'sim' => 'div-endereco',
                            'nao' => 'div-justificativa-disponibilizado'
                        ],
                    ],
                ])
            ?>

            <div class="div-endereco d-none">
                <?= $form->field($modelPublicacao, 'endereco_disponibilizado')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="div-justificativa-disponibilizado d-none">
                <?= $form->field($modelPublicacao, 'justificativa_disponibilizado', [
                    'labelOptions' => ['class' => 'is-required']
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <?= $form->field($modelPublicacao, 'data_publicacao')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'orientation' => 'top auto',
                            ]
                        ]
                    ]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelPublicacao, 'nome_numero')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4">
                    <?= $form->field($modelPublicacao, 'link')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="overflow-auto mt-3 pt-3 border-top">
                <?= $form->field($modelPublicacao, 'integridadeFile', [
                    'template' => Template::$t['file'],
                    'hintType' => ActiveField::HINT_SPECIAL,
                    'hintSettings' => ['placement' => 'right', 'onIconHover' => true]
                ])->fileInput()->hint('Inserir o arquivo do programa e do plano de integridade do órgão em formato PDF, já editorado para publicação.') ?>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger">
        <div class="card-body pb-0">
            <h6 class="text-danger border-bottom pb-3 mb-3">
                <?= Universal::icon('fas fa-exclamation-triangle') ?>
                Termos
            </h6>

            <?= $form->field($modelPublicacao, 'ciente_conteudo')->checkbox([
                'uncheck' => false,
                'label' => Html::tag(
                    'span',
                    'Informo que a autoridade máxima da organização está ciente e aprova os conteúdos do
                        programa e do plano de integridade inseridos no sistema',
                    ['class' => 'is-required']
                )
            ]) ?>

            <?= $form->field($modelPublicacao, 'ciente_conclusao')->checkbox([
                'uncheck' => false,
                'label' => Html::tag(
                    'span',
                    'Estou ciente que o módulo de formulação do programa e do plano de integridade será concluído após
                        salvar as informações referentes a esta etapa.',
                    ['class' => 'is-required']
                )
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success', 'data-confirm' => 'Deseja realmente continuar?']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
    $('input[type="radio"]:checked').each(function(index) {
        showDiv($(this));
    })

    function showDiv(element) {
        let divSim = element.attr('data-sim');
        let divNao = element.attr('data-nao');

        if (element.val() == 1) {
            $('.' + divSim).removeClass('d-none');
            $('.' + divNao).addClass('d-none');
        } else {
            $('.' + divNao).removeClass('d-none');
            $('.' + divSim).addClass('d-none');
        }
    }

    $('input[type="radio"]').change(function() {
        showDiv($(this));
    });
JS;
$this->registerJs($js);
