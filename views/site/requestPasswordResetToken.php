<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $model \app\models\PasswordResetRequestForm */

$this->title = 'Solicitar redefinição de senha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">

    <div class="login-logo">
        <?= Html::img('@web/images/logo-simples.png') ?>
    </div>

    <p class="text-center">Por favor, preencha o seu email. Um link para redefinir a senha será enviado para lá</p>

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$css = <<<CSS
    .site-request-password-reset {
        width: 100%;
        max-width: 360px;
    }
CSS;

$this->registerCss($css);
