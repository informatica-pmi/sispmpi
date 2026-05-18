<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelEixo app\models\Eixo */
/* @var $searchModel app\modules\elaborar\models\pesquisa\EixoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelSubeixo app\models\Subeixo */
/* @var $searchModelSubeixo app\modules\elaborar\models\pesquisa\SubeixoSearch */
/* @var $dataProviderSubeixo yii\data\ActiveDataProvider */
/* @var $modelAcao app\models\Acao */
/* @var $searchModelAcao app\models\pesquisa\AcaoSearch */
/* @var $dataProviderAcao yii\data\ActiveDataProvider */
/* @var $planoId */
/* @var $prepareTabs */
/* @var $optionsEixo app\modules\elaborar\Eixo */

$key = Yii::$app->request->getQueryParam('key');
?>

<div class="diagnostico-form">

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a
                        class="<?= $prepareTabs['eixo'] ?>"
                        id="pills-eixo-tab"
                        data-toggle="pill"
                        href="#pills-eixo"
                        role="tab"
                        aria-controls="pills-eixo"
                        aria-selected="true"
                    >
                        1 - Eixos
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="<?= $prepareTabs['subeixo'] ?>"
                        id="pills-subeixo-tab"
                        data-toggle="pill"
                        href="#pills-subeixo"
                        role="tab"
                        aria-controls="pills-subeixo"
                        aria-selected="true"
                    >
                        2 - Subeixos
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="<?= $prepareTabs['acao'] ?>"
                        id="pills-acao-tab"
                        data-toggle="pill"
                        href="#pills-acao"
                        role="tab"
                        aria-controls="pills-acao"
                        aria-selected="true"
                    >
                        3 - Ações
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <div
                class="tab-pane fade <?= empty($key) || $key == 'eixo' ? 'show active' : '' ?>"
                id="pills-eixo"
                role="tabpanel"
                aria-labelledby="pills-eixo-tab"
            >
                <?= $this->render('../eixo/_form', [
                    'planoId' => $planoId,
                    'modelEixo' => $modelEixo,
                ]) ?>

                <?= $this->render('../eixo/index', [
                    'searchModel' => $searchModelEixo,
                    'dataProvider' => $dataProviderEixo,
                ]) ?>
            </div>

            <div
                class="tab-pane fade <?= $key == 'subeixo' ? 'show active' : '' ?>"
                id="pills-subeixo"
                role="tabpanel"
                aria-labelledby="pills-subeixo-tab"
            >
                <?= $this->render('../subeixo/_form', [
                    'planoId' => $planoId,
                    'modelSubeixo' => $modelSubeixo,
                    'optionsEixo' => $optionsEixo,
                ]) ?>

                <?= $this->render('../subeixo/index', [
                    'planoId' => $planoId,
                    'searchModel' => $searchModelSubeixo,
                    'dataProvider' => $dataProviderSubeixo,
                ]) ?>
            </div>

            <div
                class="tab-pane fade <?= $key == 'acao' ? 'show active' : '' ?>"
                id="pills-acao"
                role="tabpanel"
                aria-labelledby="pills-acao-tab"
            >
                <?= $this->render('../acao/_form', [
                    'planoId' => $planoId,
                    'modelAcao' => $modelAcao,
                    'optionsEixo' => $optionsEixo,
                ]) ?>

                <?= $this->render('../acao/index', [
                    'planoId' => $planoId,
                    'searchModel' => $searchModelAcao,
                    'dataProvider' => $dataProviderAcao,
                ]) ?>
            </div>
        </div>
    </div>
</div>
