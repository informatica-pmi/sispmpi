<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use wbraganca\dynamicform\DynamicFormWidget;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $models app\modules\admin\models\OrgaoContabil[] */
/* @var $form kartik\form\ActiveForm */
?>

<div class="orgao-contabil-form">

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 999, // the maximum times, an element can be cloned (default 999)
        'min' => 1, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $models[0],
        'formId' => 'orgao-form-dynamic',
        'formFields' => [
            'ano',
            'orcamento',
            'quantitativo_pessoal',
        ],
    ]); ?>

    <div class="card card-outline card-secondary">
        <div class="card-body container-items">
            <h6 class="border-bottom pb-3 mb-3">
                <?= Universal::icon('fas fa-info') ?> Informações complementares
            </h6>

            <?php foreach ($models as $index => $model) : ?>
                <div class="item card">
                    <div class="card-body">
                        <?php
                        // necessary for update action.
                        if (!$model->isNewRecord) :
                            echo Html::activeHiddenInput($model, "[{$index}]id");
                        endif;
                        ?>

                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]ano")->widget(MaskedInput::className(), [
                                    'mask' => '9999'
                                ]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]orcamento")->widget(MaskMoney::className()) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]quantitativo_pessoal")
                                    ->textInput(['maxlength' => true])
                                ?>
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
