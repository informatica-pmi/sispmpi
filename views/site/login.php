<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <div class="login-logo">
        <?= Html::img('@web/images/logo-simples.png', ['alt' => 'Logo', 'class' => 'img-fluid']) ?>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form'
    ]); ?>

    <?= $form->field($model, 'username')
        ->textInput(['placeholder' => $model->getAttributeLabel('username')])
        ->label(false)
    ?>

    <?= $form->field($model, 'password')
        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
        ->label(false)
    ?>

    <?= $form->field($model, 'rememberMe', ['labelOptions' => ['class' => 'font-weight-normal remember-custom-color']])
        ->checkbox(['custom' => true, 'switch' => true])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?= Html::a(
        'Esqueceu sua senha?',
        ['/site/request-password-reset'],
        ['class' => 'font-weight-semi-bold forget-password']
    ) ?>

    <?php ActiveForm::end(); ?>
</div>

<?php

$css = <<<CSS
    .site-login {
        width: 100%;
        max-width: 300px;
    }
CSS;

$this->registerCss($css);
