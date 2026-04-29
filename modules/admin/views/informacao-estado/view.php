<?php

use kartik\detail\DetailView;
use app\components\helpers\Button;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\InformacaoEstado */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Informações do Estado', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="informacao-estado-view">

    <p>
        <?= Button::generate(
            'informacao-estado-editar',
            null,
            'far fa-edit',
            'Editar',
            ['update', 'id' => $model->id],
            false,
            '',
            'primary'
        ) ?>

        <?= Button::generate(
            'informacao-estado-apagar',
            null,
            'fas fa-trash',
            'Apagar',
            ['delete', 'id' => $model->id],
            false,
            '',
            'danger',
            true
        ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'mb-3'],
        'hAlign' => 'left',
        'attributes' => [
            'id',
            'ano',
            [
                'attribute' => 'orcamento',
                'value' => Universal::convertCurrency($model->orcamento),
            ],
            'quantitativo_pessoal',
        ],
    ]) ?>

</div>
