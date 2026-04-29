<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\User;
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Breadcrumbs;
use app\components\helpers\Universal;
use lavrentiev\widgets\toastr\NotificationFlash;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item home d-none d-sm-inline-block">
                    <?= Html::a('Home', Yii::$app->homeUrl, ['class' => 'nav-link']) ?>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        <?= Universal::icon('fas fa-user-circle') ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if (ArrayHelper::isIn(User::PERFIL_ADMINISTRADOR, ArrayHelper::getColumn(Yii::$app->user->identity->authAssignments, 'item_name'))) : ?>
                            <h6 class="dropdown-header text-left text-sm text-black-50">Alterar perfil</h6>

                            <?php foreach (Yii::$app->user->identity->authAssignments as $authAssignment) : ?>
                                <?= Html::a(
                                    User::getPerfil() === $authAssignment->item_name
                                        ? Html::tag('span', $authAssignment->item_name, ['class' => 'mr-2']) . Html::tag('i', '', ['class' => 'fas fa-check text-success'])
                                        : Html::tag('span', $authAssignment->item_name),
                                    ['@admin/alterar-perfil/update', 'to' => $authAssignment->item_name],
                                    ['class' => 'dropdown-item text-sm']
                                ) ?>
                            <?php endforeach; ?>

                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        <?= Html::a('Perfil', ['/user/view', 'id' => User::getIdentidade('id')], ['class' => 'dropdown-item text-sm']) ?>

                        <?= Html::a('Sair', ['/site/logout'], [
                            'class' => 'dropdown-item text-sm',
                            'data' => [
                                'method' => 'post',
                                'confirm' => 'Deseja realmente sair?'
                            ]
                        ]) ?>
                    </div>
                </li>

                <li class="nav-item d-sm-inline-block">
                    <?= Html::a(
                        Universal::icon('fas fa-adjust'),
                        '#',
                        [
                            'class' => 'btn-dark-mode nav-link',
                            'title' => 'Alto contraste',
                            'data' => [
                                'toggle' => 'tooltip',
                                'placement' => 'bottom',
                            ]
                        ]
                    ) ?>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?= Html::a(
                Html::img(
                    '@web/images/logo-sidebar.png',
                    [
                        'alt' => 'Logo',
                        'class' => 'brand-image',
                        'style' => 'opacity: .8'
                    ]
                ) .
                    Html::tag('span', 'PMPI', ['class' => 'brand-text font-weight']),
                Yii::$app->homeUrl,
                ['class' => 'brand-link']
            ) ?>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul
                        class="nav nav-pills nav-sidebar flex-column"
                        data-widget="treeview"
                        role="menu"
                        data-accordion="false"
                    >
                        <li class="nav-header">PROGRAMAS DE INTEGRIDADE</li>

                        <li class="nav-item home">
                            <?= Html::a(
                                Universal::icon('far fa-circle nav-icon mr-2') .
                                    Html::tag('p', 'Módulos'),
                                Yii::$app->homeUrl,
                                ['class' => 'nav-link']
                            ) ?>
                        </li>

                        <?php if (Universal::temPermissao('indicador-index')) : ?>
                            <li class="nav-item">
                                <?= Html::a(
                                    Universal::icon('far fa-circle nav-icon mr-2') .
                                        Html::tag('p', 'Indicadores'),
                                    ['@admin/indicador/index'],
                                    ['class' => 'nav-link']
                                ) ?>
                            </li>
                        <?php endif; ?>

                        <?php if (Universal::temPermissao('plano-integridade-reabertura')) : ?>
                            <li class="nav-item">
                                <?= Html::a(
                                    Universal::icon('far fa-circle nav-icon mr-2') .
                                        Html::tag('p', 'Reabertura'),
                                    ['@admin/plano-integridade-reabertura/index'],
                                    ['class' => 'nav-link']
                                ) ?>
                            </li>
                        <?php endif; ?>

                        <?php if (Universal::temPermissao('modulo-auditor')) : ?>
                            <li class="nav-item">
                                <?= Html::a(
                                    Universal::icon('far fa-circle nav-icon mr-2') .
                                        Html::tag('p', 'Versões anteriores'),
                                    ['@auditor/default'],
                                    ['class' => 'nav-link']
                                ) ?>
                            </li>
                        <?php endif; ?>

                        <?php if (Universal::temPermissao('menu-administracao')) : ?>
                            <li class="nav-header">OUTROS</li>

                            <li class="nav-item has-treeview">
                                <?= Html::a(
                                    Universal::icon('nav-icon far fa-folder mr-2') .
                                        Html::tag('p', 'Administração' .
                                            Universal::icon('right fas fa-angle-left')),
                                    '#',
                                    ['class' => 'nav-link']
                                ) ?>

                                <ul class="nav nav-treeview">
                                    <?php if (Universal::temPermissao('eixo-tematico-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                Html::tag('p', 'Eixos Temáticos'),
                                                ['@admin/eixo-tematico/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('fator-limitante-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Fatores Limitantes'),
                                                ['@admin/fator-limitante/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('informacao-estado-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Inf. do Estado'),
                                                ['@admin/informacao-estado/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('instrumento-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Instrumentos'),
                                                ['@admin/instrumento/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('orgao-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Órgãos'),
                                                ['@admin/orgao/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('stakeholder-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Stakeholders'),
                                                ['@admin/stakeholder/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('unidade-administrativa-listar')) : ?>
                                        <li class="nav-item">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag('p', 'Unidades Admin.'),
                                                ['/unidade-administrativa/index'],
                                                ['class' => 'nav-link']
                                            ) ?>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (Universal::temPermissao('menu-usuarios')) : ?>
                                        <li class="nav-item has-treeview">
                                            <?= Html::a(
                                                Universal::icon('far fa-circle nav-icon mr-2') .
                                                    Html::tag(
                                                        'p',
                                                        'Usuários' . Universal::icon('right fas fa-angle-left')
                                                    ),
                                                '#',
                                                ['class' => 'nav-link has-lvl-three']
                                            ) ?>
                                            <ul class="nav nav-treeview">

                                                <?php if (Universal::temPermissao('usuario-listar')) : ?>
                                                    <li class="nav-item">
                                                        <?= Html::a(
                                                            Universal::icon('far fa-dot-circle nav-icon') .
                                                                Html::tag('p', 'Listar'),
                                                            ['/user/index'],
                                                            ['class' => 'nav-link']
                                                        ) ?>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (Universal::temPermissao('usuario-cadastrar-cge')) : ?>
                                                    <li class="nav-item">
                                                        <?= Html::a(
                                                            Universal::icon('far fa-dot-circle nav-icon') .
                                                                Html::tag('p', 'Cadastrar CGE'),
                                                            ['@admin/user-cge/create'],
                                                            ['class' => 'nav-link']
                                                        ) ?>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (Universal::temPermissao('usuario-cadastrar-auditor')) : ?>
                                                    <li class="nav-item">
                                                        <?= Html::a(
                                                            Universal::icon('far fa-dot-circle nav-icon') .
                                                                Html::tag('p', 'Cadastrar Auditor'),
                                                            ['@admin/user-auditor/create'],
                                                            ['class' => 'nav-link']
                                                        ) ?>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (Universal::temPermissao('usuario-cadastrar-outros')) : ?>
                                                    <li class="nav-item">
                                                        <?= Html::a(
                                                            User::getPerfil() === User::PERFIL_ADMINISTRADOR ?
                                                                    Universal::icon('far fa-dot-circle nav-icon') .
                                                                        Html::tag('p', 'Cadastrar Outros') :
                                                                    Universal::icon('far fa-dot-circle nav-icon') .
                                                                        Html::tag('p', 'Cadastrar'),
                                                            ['/user-outro/create'],
                                                            ['class' => 'nav-link']
                                                        ) ?>
                                                    </li>
                                                <?php endif; ?>

                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header border-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="m-0 text-white font-weight-normal"><?= Html::encode($this->title) ?></h4>
                        </div>
                        <div class="col-sm-6">
                            <?= Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                'options' => ['class' => 'float-right small']
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <?php if (Yii::$app->params['darkIsActive']) : ?>
                        <div class="loading-dark-mode">
                            <div class="spinner spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h5>C A R R E G A N D O</h5>
                        </div>
                    <?php endif; ?>

                    <div class="submit-loading d-none bg-light">
                        <div class="spinner spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <h5>CARREGANDO AS INFORMAÇÕES, AGUARDE...</h5>
                    </div>

                    <div class="card">
                        <div class="card-body pb-1">
                            <?= $content ?>
                        </div>
                    </div>

                    <?= NotificationFlash::widget([
                        'options' => [
                            "closeButton" => true,
                            "debug" => false,
                            "newestOnTop" => false,
                            "progressBar" => true,
                            "positionClass" => NotificationFlash::POSITION_TOP_CENTER,
                            "preventDuplicates" => false,
                            "onclick" => null,
                            "showDuration" => "300",
                            "hideDuration" => "1000",
                            "timeOut" => "5000",
                            "extendedTimeOut" => "1000",
                            "showEasing" => "swing",
                            "hideEasing" => "linear",
                            "showMethod" => "fadeIn",
                            "hideMethod" => "fadeOut"
                        ]
                    ]); ?>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            &copy; Diretoria de Tecnologia da Informação e Comunicação - Controladoria-Geral do Estado
            <div class="float-right d-none d-sm-inline-block">
                <span>Version 3.5.1</span>
            </div>
        </footer>

    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
