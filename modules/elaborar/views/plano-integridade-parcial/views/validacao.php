<?php

use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;

/* @var $this yii\web\View */
/* @var $modelValidacao app\modules\elaborar\models\Validacao */
?>
<div class="validacao-view">
    <h2>Validação geral</h2>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelValidacao->getAttributeLabel('data_inicio'),
            Universal::convertDate($modelValidacao->data_inicio)
        ); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>: %s',
            $modelValidacao->getAttributeLabel('data_conclusao'),
            Universal::convertDate($modelValidacao->data_conclusao)
        ); ?>
    </p>

    <p><strong><?= $modelValidacao->getAttributeLabel('stakeholderIds') ?></strong></p>

    <p>
        <?= implode(", ", ArrayHelper::getColumn($modelValidacao->validacaoStakeholders, function ($element) {
            $stakeholder = $element['stakeholder'];

            return is_null($stakeholder['orgao_id']) ?
                $stakeholder['nome'] :
                "<span class='text-specific'>{$stakeholder['nome']}</span>";
        })); ?>
    </p>

    <p>
        <?= sprintf(
            '<strong>%s</strong>%s',
            $modelValidacao->getAttributeLabel('info_complementar'),
            Universal::stripTags($modelValidacao->info_complementar)
        ); ?>
    </p>
</div>
