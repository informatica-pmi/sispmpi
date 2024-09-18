<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $model \app\models\ResetPasswordForm */

$this->title = 'Redefinir senha';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">

    <div class="login-logo">
        <?= Html::img('@web/images/logo-simples.png') ?>
    </div>

    <p class="text-center">Por favor, escolha sua nova senha</p>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

    <?= $form->field($model, 'password')
        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
        ->label(false)
    ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php

$css = <<<CSS
    .site-reset-password {
        width: 100%;
        max-width: 360px;
    }
CSS;

$this->registerCss($css);
