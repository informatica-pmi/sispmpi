<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use lavrentiev\widgets\toastr\NotificationFlash;
use app\assets\AppAsset;

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
    <?php $this->registerCssFile('@web/css/clear.css') ?>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <div class="content-body">
        <?= $content ?>
    </div>

    <?= NotificationFlash::widget([
        'options' => [
            "closeButton" => true,
            "debug" => false,
            "newestOnTop" => false,
            "progressBar" => true,
            "positionClass" => NotificationFlash::POSITION_TOP_RIGHT,
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

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
