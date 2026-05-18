<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $nome */

$this->title = 'Acesso Negado';
?>
<div class="site-acesso-negado">

    <div class="login-logo">
        <?= Html::img('@web/images/logo-simples.png') ?>
    </div>

    <p class="text-center mb-1">Olá <?= $nome ?>.</p>
    <p class="text-center mb-3">
        Você não tem permissão para acessar esta página, contate o administrador para mais detalhes.
    </p>

    <?= Html::a('Página inicial', ['/site/index'], ['class' => 'text-center btn btn-custom-info w-100']) ?>
</div>

<?php

$css = <<<CSS
    .site-acesso-negado {
        width: 100%;
        max-width: 360px;
    }
CSS;

$this->registerCss($css);
