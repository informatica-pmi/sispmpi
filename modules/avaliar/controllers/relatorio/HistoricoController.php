<?php

namespace app\modules\avaliar\controllers\relatorio;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use app\components\helpers\Audit;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Historico;
use app\models\PlanoIntegridade;
use app\models\Servidor;
use app\models\Status;
use app\models\User;
use app\modules\executar\models\AcaoExecucao;

class HistoricoController extends Controller
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
     * Gerar relátorio com o historico de alterações
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelServidor = new Servidor();

        $modelAcaoExecucao = new AcaoExecucao();

        $queryParamsHistoricoSearch = Yii::$app->request->getQueryParam('HistoricoSearch');

        $dataInicio = $queryParamsHistoricoSearch['data_inicio'];

        $dataFim = $queryParamsHistoricoSearch['data_fim'];

        $dateConditions = $dataInicio > $dataFim ||
            empty($dataInicio) && !empty($dataFim) ||
            !empty($dataInicio) && empty($dataFim);

        if ($dateConditions) {
            Universal::flash('error', 'Erro no preenchimento das datas, verifique e tente novamente.');
            return $this->redirect(['@avaliar/relatorio/default/index']);
        }

        $filterAcaoIds = $queryParamsHistoricoSearch['acaoIds'];

        $withFilter = !empty($dataInicio) && !empty($dataFim);

        $queryAcaos = Acao::find()
            ->joinWith(['eixo.planoIntegridade'])
            ->where(['plano_integridade_id' => $modelPlano->id]);

        if (!empty($filterAcaoIds)) {
            $queryAcaos->andWhere(['in', 'acao.id', $filterAcaoIds]);
        }

        $modelsAcao = $queryAcaos->all();

        foreach ($modelsAcao as $modelAcao) {
            $acaoServidorIds = Audit::getRegisterIds('AcaoServidor', $modelAcao->id);

            $acaoServidorsFilterServidor = Historico::find()
                ->where(['campo' => 'servidor_id', 'model' => 'AcaoServidor'])
                ->andWhere(['in', 'id_registro', $acaoServidorIds])
                ->all();

            $servidorIds = array_unique(ArrayHelper::getColumn($acaoServidorsFilterServidor, function ($elem) {
                return empty($elem->novo_valor) ? $elem->antigo_valor : $elem->novo_valor;
            }));

            $historicos['Servidor'][$modelAcao->id] = Audit::getHistorico(
                'Servidor',
                $servidorIds,
                $withFilter,
                $dataInicio,
                $dataFim
            );

            $acaoExecucaoIds = Audit::getRegisterIds('AcaoExecucao', $modelAcao->id);

            $historicos['AcaoExecucao'][$modelAcao->id] = Audit::getHistorico(
                'AcaoExecucao',
                $acaoExecucaoIds,
                $withFilter,
                $dataInicio,
                $dataFim
            );
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'modelsAcao' => $modelsAcao,
                'historicos' => $historicos,
                'modelServidor' => $modelServidor,
                'modelAcaoExecucao' => $modelAcaoExecucao,
                'withFilter' => $withFilter,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim,
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Relatório com histórico de alterações realizadas',
                'SetHeader' => ['SISPMPI - Relatório de historico||Gerado em: ' . date("d/m/Y") . ' às ' . date('H:i')],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SISPMPI',
                'SetCreator' => 'SISPMPI',
            ]
        ]);

        return $pdf->render();
    }
}
