<?php

/* @var $this yii\web\View */
/* @var $modelDiagnostico app\modules\elaborar\models\Diagnostico */
/* @var $modelDiagnosticoInfoEstrategica app\modules\elaborar\models\DiagnosticoInfoEstrategica */
/* @var $modelDiagnosticoResultado app\modules\elaborar\models\DiagnosticoResultado */
/* @var $modelDiagnosticoResultadoPrograma app\modules\elaborar\models\DiagnosticoResultado */
/* @var $modelDiagnosticoInstrumento app\modules\elaborar\models\DiagnosticoInstrumento */
/* @var $modelsInstrumento app\models\Instrumento[] */
/* @var $optionsInstrumento app\models\Instrumento[] */
/* @var $optionsEixoTematico app\models\EixoTematico[] */
/* @var $prepareTabs */
?>

<div class="diagnostico-form">

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                <?php foreach ($prepareTabs as $index => $prepareTab) : ?>
                    <?php
                    $classLink = $prepareTab['pageKey'] === $modelDiagnostico->page_key &&
                        $prepareTab['hasPermission'] ?
                            'active' :
                            '';

                    $classLink .= $prepareTab['pageKey'] <= $modelDiagnostico->page_key &&
                        $prepareTab['hasPermission'] ?
                            '' :
                            'disabled';
                    ?>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= $classLink ?>"
                            id="pills-<?= $index ?>-tab"
                            data-toggle="pill"
                            href="#pills-<?= $index ?>"
                            role="tab"
                            aria-controls="pills-<?= $index ?>"
                            aria-selected="true"
                        >
                            <?= $prepareTab['label'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body tab-content pb-1">
            <div
                class="tab-pane fade <?= $modelDiagnostico->page_key == 0 ? 'show active' : '' ?>"
                id="pills-informacoes-estrategicas"
                role="tabpanel"
                aria-labelledby="pills-informacoes-estrategicas-tab"
            >
                <?php if ($modelDiagnostico->page_key >= 0 && $prepareTabs['informacoes-estrategicas']['hasPermission']) : ?>
                    <?= $this->render('../info-estrategica/_form', [
                        'diagnosticoId' => $modelDiagnostico->id,
                        'modelDiagnosticoInfoEstrategica' => $modelDiagnosticoInfoEstrategica,
                    ]) ?>
                <?php else : ?>
                    <span class="text-muted">
                        Você não tem acesso a esta página, atualmente está sendo preenchido o
                        formulário <u>Informações estratégicas</u>.
                    </span>
                <?php endif; ?>
            </div>

            <div
                class="tab-pane fade <?= $modelDiagnostico->page_key == 1 ? 'show active' : '' ?>"
                id="pills-instrumentos"
                role="tabpanel"
                aria-labelledby="pills-instrumentos-tab"
            >
                <?php if ($modelDiagnostico->page_key >= 1 && $prepareTabs['instrumentos']['hasPermission']) : ?>
                    <?= $this->render('../instrumento/_form', [
                        'diagnosticoId' => $modelDiagnostico->id,
                        'modelDiagnosticoInstrumento' => $modelDiagnosticoInstrumento,
                        'modelDiagnosticoResultado' => $modelDiagnosticoResultado,
                        'modelsInstrumento' => $modelsInstrumento,
                        'optionsInstrumento' => $optionsInstrumento
                    ]) ?>
                <?php else : ?>
                    <span class="text-muted">
                        Você não tem acesso a esta página, atualmente está sendo preenchido o
                        formulário <u>instrumentos</u>.
                    </span>
                <?php endif; ?>
            </div>

            <div
                class="tab-pane fade<?= $modelDiagnostico->page_key == 2 ? 'show active' : '' ?>"
                id="pills-resultados-diagnostico"
                role="tabpanel"
                aria-labelledby="pills-resultados-diagnostico-tab"
            >
                <?php if ($modelDiagnostico->page_key >= 2 && $prepareTabs['resultados-diagnostico']['hasPermission']) : ?>
                    <?= $this->render('../resultado/_form', [
                        'diagnosticoId' => $modelDiagnostico->id,
                        'modelDiagnosticoResultadoPrograma' => $modelDiagnosticoResultadoPrograma,
                        'optionsEixoTematico' => $optionsEixoTematico
                    ]) ?>
                <?php else : ?>
                    <span class="text-muted">
                        Você não tem acesso a esta página, atualmente está sendo preenchido o
                        formulário <u>Resultados do diagnóstico</u>.
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
