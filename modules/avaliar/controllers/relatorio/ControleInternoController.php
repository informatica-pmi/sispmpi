<?php

namespace app\modules\avaliar\controllers\relatorio;

use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use yii\helpers\ArrayHelper;

class ControleInternoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Gerar relátorios do controle interno
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $queryParamsHistoricoSearch = Yii::$app->request->getQueryParam('HistoricoSearch');

        $filterAcaoIds = $queryParamsHistoricoSearch['acaoIds'];

        $dataInicio = $queryParamsHistoricoSearch['data_inicio'];

        $dataFim = $queryParamsHistoricoSearch['data_fim'];

        $dateConditions = $dataInicio > $dataFim ||
            empty($dataInicio) && !empty($dataFim) ||
            !empty($dataInicio) && empty($dataFim);

        if ($dateConditions) {
            Universal::flash('error', 'Erro no preenchimento das datas, verifique e tente novamente.');
            return $this->redirect(['@avaliar/relatorio/default/index']);
        }

        $queryPlanoIntegridadeRecomendacaos = $modelPlano->getPlanoIntegridadeRecomendacaos();

        $queryPlanoPromover = $modelPlano->orgao->getPromoverIntegridades();

        $eixoIds = ArrayHelper::getColumn($modelPlano->eixos, 'id');

        $queryAcaos = Acao::find()->joinWith(['acaoAvaliacaoRecomendacaos']);

        $withFilter = !empty($dataInicio) && !empty($dataFim);

        if ($withFilter) {
            $dataInicio .= ' 00:00:00';
            $dataFim .= ' 23:59:59';

            $queryPlanoIntegridadeRecomendacaos->andWhere(['between', 'created_at', $dataInicio, $dataFim]);
            $queryPlanoPromover = $queryPlanoPromover->andWhere(['between', 'created_at', $dataInicio, $dataFim]);
        }

        $modelsPlanoIntegridadeRecomendacao = $queryPlanoIntegridadeRecomendacaos->orderBy(['id' => SORT_DESC])
            ->all();

        $modelsPlanoPromover = $queryPlanoPromover->orderBy(['data' => SORT_DESC])->all();

        if (empty($filterAcaoIds)) {
            $queryAcaos->andWhere(['in', 'acao.eixo_id', $eixoIds]);
        } else {
            $queryAcaos->andWhere(['in', 'acao.id', $filterAcaoIds]);
        }

        $modelsAcao = $queryAcaos->all();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'userOrgaoNome' => $userOrgaoNome,
                'modelsPlanoIntegridadeRecomendacao' => $modelsPlanoIntegridadeRecomendacao,
                'modelsPlanoPromover' => $modelsPlanoPromover,
                'modelsAcao' => $modelsAcao,
                'withFilter' => $withFilter,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Relatório de controle interno',
                'SetHeader' => [
                    'SISPMPI - Relatório de controle interno || Gerado em: ' . date("d/m/Y") . ' às ' . date('H:i')
                ],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SISPMPI',
                'SetCreator' => 'SISPMPI',
            ]
        ]);

        return $pdf->render();
    }
}
