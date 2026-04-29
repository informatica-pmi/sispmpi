<?php

use kartik\datecontrol\DateControl;
use app\models\Status;
use app\base\Template;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelGrupoInstituido app\models\GrupoInstituido */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-instituido-form">

    <?= $form->field($modelGrupoInstituido, 'formalmente', ['template' => Template::$t['radio']])
        ->radioButtonGroup(Status::getResposta())
    ?>

    <div class="card card-outline card-secondary d-none" id="content-formal">
        <div class="card-body">
            <h6 class="border-bottom pb-3 mb-3">
                <?= Universal::icon('fas fa-info') ?> Informações complementares
            </h6>

            <div id="formal-sim" class="d-none">
                <?= $form->field($modelGrupoInstituido, 'nome_numero')->textInput(['maxlength' => true]) ?>

                <div class="row">
                    <div class="col-sm-3">
                        <?= $form->field($modelGrupoInstituido, 'data_publicacao')->widget(DateControl::className(), [
                            'type' => DateControl::FORMAT_DATE,
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'orientation' => 'bottom auto',
                                ]
                            ]
                        ]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($modelGrupoInstituido, 'dias_conclusao')->textInput() ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($modelGrupoInstituido, 'link')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>

            <div id="formal-nao" class="d-none">
                <?= $form->field($modelGrupoInstituido, 'data_inicio')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                            'orientation' => 'bottom auto',
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>

</div>

<?php

// 1 = Status::STATUS_SIM
// 0 = Status::STATUS_NAO
$js = <<<JS
    formal();

    function formal() {
        if($('input[name="GrupoInstituido[formalmente]"]:checked').val() == 1) {
            $('#content-formal').removeClass('d-none');
            $('#formal-nao').addClass('d-none');
            $('#formal-sim').removeClass('d-none');
        } else if ($('input[name="GrupoInstituido[formalmente]"]:checked').val() == 0) {
            $('#content-formal').removeClass('d-none');
            $('#formal-sim').addClass('d-none');
            $('#formal-nao').removeClass('d-none');
        }
    }

    $('input[name="GrupoInstituido[formalmente]"').change(function() {
        formal();
    });
JS;

$this->registerJs($js);
?>

