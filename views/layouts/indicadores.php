<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\web\JqueryAsset;

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
    <style>
        .circle {
            width: 10px;
            height: 10px;
        }
        .font-weight-semibold {
            font-weight: 600;
        }
        .card-result {
            border-radius: 25px;
        }
        .card-result-pt {
            padding-top: 2.875rem;
        }
        .icon {
            width: 60px;
            background: #775E94;
            top: -30px;
        }
        .c-radius {
            border-radius: 15px;
        }
        .c-shadow {
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.25);
        }
        .text-purple {
            color: #775E94;
        }
        .bg-green-chart {
            background: #37D35A;
        }
        .bg-purple-chart {
            background: #775E94;
        }
        .bg-red-chart {
            background: #FF5151;
        }
        .bg-yellow-chart {
            background: #D1CB2B;
        }
        .bg-red-chart-light {
            background: #FFD3D8;
        }
        .bg-green-chart-light {
            background: #BEFECC;
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>
    <div class="content-body container-fluid p-5 bg-light">
        <?= $content ?>
    </div>

    <footer class="main-footer ml-0">
        &copy; Diretoria de Tecnologia da Informação e Comunicação - Controladoria-Geral do Estado
        <div class="float-right d-none d-sm-inline-block">
            <span>Version 3.4.4</span>
        </div>
    </footer>

    <?php $this->registerJsFile('@web/js/indicadores.js', ['depends' => [JqueryAsset::class]]) ?>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
