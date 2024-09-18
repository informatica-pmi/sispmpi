<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\widgets\TinyMCE;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use app\base\Txt;
use app\models\UnidadeAdministrativa;
use app\models\User;

/* @var $this yii\web\View */
/* @var $modelAcao app\models\Acao */
/* @var $form kartik\form\ActiveForm */
/* @var $planoId */
/* @var $optionsEixo app\modules\elaborar\Eixo */
?>

<div class="acao-form">

    <?php $form = ActiveForm::begin([
        'action' => $modelAcao->isNewRecord ?
            ['@elaborar/redacao/acao/create', 'planoId' => $planoId] :
            ['@elaborar/redacao/acao/update', 'acaoId' => $modelAcao->id]
    ]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelAcao, 'eixo_id')->dropDownList($optionsEixo, ['prompt' => Txt::$t['prompt']]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelAcao, 'subeixo_id')->widget(DepDrop::className(), [
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['theme' => Select2::THEME_KRAJEE_BS5,],
                'pluginOptions' => [
                    'idParam' => 'id',
                    'nameParam' => 'titulo',
                    'initialize' => true,
                    'depends' => ['acao-eixo_id'],
                    'placeholder' => Txt::$t['prompt'],
                    'url' => Url::to(['@elaborar/redacao/subeixo/list'])
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelAcao, 'numero')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelAcao, 'titulo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($modelAcao, 'descricao')->widget(TinyMce::className()) ?>

    <?= $form->field($modelAcao, 'unidade_executora')->dropDownList(
        ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->orderBy(['nome' => 'SORT_ASC'])
                ->all(),
            'id',
            'nome'
        ),
        ['prompt' => Txt::$t['prompt']]
    ) ?>

    <?= $form->field($modelAcao, 'unidadeApoioIds')->multiselect(
        ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->orderBy(['nome' => 'SORT_ASC'])
                ->all(),
            'id',
            'nome'
        ),
        ['container' => ['class' => 'multiselect']]
    ) ?>

    <?= $form->field($modelAcao, 'objetivo')->widget(TinyMce::className()) ?>

    <?= $form->field($modelAcao, 'beneficio_instituicao')->widget(TinyMce::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Voltar', ['@elaborar'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
