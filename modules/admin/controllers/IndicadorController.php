<?php

namespace app\modules\admin\controllers;

use app\components\helpers\Universal;
use app\models\Status;
use app\modules\admin\models\InformacaoEstado;
use app\modules\admin\models\pesquisa\VIndicadoresSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class IndicadorController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (!Universal::temPermissao('indicador-index')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $this->layout = '//indicadores';

        $totalCount = (new Query())->from('v_indicadores')->count();

        $allOrgaos = (new Query())
            ->select([
                'IFNULL(`plano_integridade_status`, 3) as status',
                'COUNT(*) as total',
                'ROUND((COUNT(*) / ' . $totalCount . ' * 100)) as percentage'
            ])
            ->from('v_indicadores')
            ->groupBy('status')
            ->all();

        $resultAllOrgaos = ArrayHelper::index($allOrgaos, 'status');

        $resultTipoOrgaos = (new Query())
            ->select([
                'orgao_tipo',
                'COUNT(*) as total',
                'ROUND(((SELECT COUNT(*) FROM v_indicadores WHERE orgao_tipo = vi.orgao_tipo AND plano_integridade_status = ' . Status::PLANO_PUBLICADO . ') / COUNT(*) * 100), 2) as publicados',
                'ROUND(((SELECT COUNT(*) FROM v_indicadores WHERE orgao_tipo = vi.orgao_tipo AND plano_integridade_status = ' . Status::PLANO_ELABORACAO . ') / COUNT(*) * 100), 2) as elaboracao',
                'ROUND(((SELECT COUNT(*) FROM v_indicadores WHERE orgao_tipo = vi.orgao_tipo AND (plano_integridade_status = ' . Status::PLANO_N_INICIADO . ' OR plano_integridade_status IS NULL)) / COUNT(*) * 100), 2) as naoIniciados'
            ])
            ->from('v_indicadores as vi')
            ->groupBy(['orgao_tipo'])
            ->all();

        $informacaoEstado = InformacaoEstado::findOne(['ano' => date('Y')]);

        if (is_null($informacaoEstado)) {
            Universal::flash('error', 'Nenhuma informação do estado cadastrada para o ano atual.');

            return $this->redirect(Yii::$app->homeUrl);
        }

        $allOrcamento = (new Query())
            ->select([
                'IFNULL(plano_integridade_status, 3) as status',
                'IFNULL(ROUND((SUM(orgao_orcamento) / ' . $informacaoEstado->orcamento . ' * 100), 2), 0.00) as percentage'
            ])
            ->from('v_indicadores')
            ->groupBy('status')
            ->all();

        $resultOrcamento = ArrayHelper::index($allOrcamento, 'status');

        $allQuantitativoPessoal = (new Query())
            ->select([
                'IFNULL(plano_integridade_status, 3) as status',
                'IFNULL(ROUND((SUM(orgao_pessoal) / ' . $informacaoEstado->quantitativo_pessoal . ' * 100), 2), 0.00) as percentage'
            ])
            ->from('v_indicadores')
            ->groupBy('status')
            ->all();

        $resultQuantitativoPessoal = ArrayHelper::index($allQuantitativoPessoal, 'status');

        $resultTempoMedio = (new Query())
            ->select([
                '(SUM(DATEDIFF(plano_integridade_data_conclusao, plano_integridade_data_inicio)) / COUNT(*)) as tempo_medio'
            ])
            ->from('v_indicadores')
            ->where(['plano_integridade_status' => Status::PLANO_PUBLICADO])
            ->one();

        $searchModel = new VIndicadoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'totalCount' => $totalCount,
            'resultAllOrgaos' => $resultAllOrgaos,
            'resultTipoOrgaos' => $resultTipoOrgaos,
            'informacaoEstado' => $informacaoEstado,
            'resultOrcamento' => $resultOrcamento,
            'resultQuantitativoPessoal' => $resultQuantitativoPessoal,
            'resultTempoMedio' => $resultTempoMedio,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
