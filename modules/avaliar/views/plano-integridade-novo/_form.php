<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\base\Template;
use app\models\PlanoIntegridadeNovo;
use app\models\Status;
use yii\widgets\Pjax;

/* @var $modelPlanoIntegridadeNovo app\models\PlanoIntegridadeNovo */
/* @var $isNewVersion */
?>
<div class="plano-integridade-novo-form">
    <?php Pjax::begin(['timeout' => 10000]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form-plano-integridade-novo',
        'options' => [
            'data-pjax' => 1
        ]
    ]) ?>

    <div class="card card-outline card-secondary">
        <div class="card-body pb-0">

            <?php if ($isNewVersion) : ?>
                <h5 class="card-title font-weight-semi-bold mb-2">
                    Autorizar a formulação de nova versão do atual programa de integridade
                </h5>

                <p class="card-text font-weight-semi-bold text-justify">
                    <i>
                        Alerta: a comissão de integridade solicitou autorização para a formulação de nova versão do
                        atual programa de integridade!
                    </i>
                </p>

                <p class="card-text text-justify">
                    A formulação de nova versão do atual programa de integridade consiste na elaboração de
                    aprimoramentos ao atual programa de integridade da organização.
                </p>

                <p class="card-text text-justify">
                    Na revisão é possível modificar, inserir ou excluir ações do plano de integridade, assim como
                    alterar outros conteúdos alimentados anteriormente no módulo 1 do sistema.
                </p>

                <p class="card-text text-justify">
                    Ao autorizar a formulação de nova versão, o módulo 1 do sistema será habilitado e permitirá que a
                    comissão de integridade altere o atual programa de integridade.
                </p>

                <p class="card-text text-justify">
                    Todos os registros já realizados nos demais módulos do sistema serão mantidos.
                </p>
            <?php else : ?>
                <h5 class="card-title font-weight-semi-bold mb-2">
                    Autorizar a formulação de nova edição do programa de integridade
                </h5>

                <p class="card-text font-weight-semi-bold text-justify">
                    <i>
                        Alerta: a comissão de integridade solicitou autorização para a formulação de nova edição do
                        programa de integridade!
                    </i>
                </p>

                <p class="card-text text-justify">
                    A formulação de nova edição do programa de integridade consiste na reformulação de todo o conteúdo
                    do atual programa e plano de integridade da organização.
                </p>

                <p class="card-text text-justify">
                    Ao autorizar a formulação de nova edição, o módulo 1 do sistema será reabilitado, permitindo que os
                    usuários cadastrados com o perfil 'GT' insiram o conteúdo de todas as etapas relacionadas com o
                    processo de formulação do programa e do plano de integridade.
                </p>

                <p class="card-text text-justify">
                    Nenhum registro já realizado no atual programa de integridade será mantido no sistema.
                </p>

                <p class="card-text text-justify">
                    Antes de autorizar esta ação, confirme com a comissão de integridade se realmente desejam iniciar o
                    processo de formulação de nova edição do programa e do plano de integridade da organização.
                </p>
            <?php endif;?>

            <hr>

            <?= $form->field($modelPlanoIntegridadeNovo, 'autorizado', ['template' => Template::$t['radio']])
                ->radioButtonGroup(Status::getResposta())
                ->label(
                    $isNewVersion ?
                    'Deseja autorizar a revisão do atual programa de integridade?' :
                    'Deseja autorizar a criação de uma nova edição do programa de integridade?'
                )
            ?>

            <div class="div-justificativa d-none">
                <?= $form->field($modelPlanoIntegridadeNovo, 'justificativa', [
                    'labelOptions' => ['class' => 'is-required']
                ])->textarea() ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>

    <?php Pjax::end() ?>
</div>

<?php

$js = <<<JS
    function showDiv(element) {
        if (element.val() == 1) {
            $('.div-justificativa').addClass('d-none');
        } else {
            $('.div-justificativa').removeClass('d-none');
        }
    }

    $('input[type="radio"]').change(function() {
        showDiv($(this));
    });

    $(document).on("pjax:beforeSend", function() {
        $('.submit-loading').removeClass('d-none');
    });
JS;

$this->registerJs($js);
