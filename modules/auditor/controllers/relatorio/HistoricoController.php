<?php

namespace app\modules\auditor\controllers\relatorio;

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
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionIndex($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-auditor', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelServidor = new Servidor();

        $modelAcaoExecucao = new AcaoExecucao();

        $dataInicio = '';

        $dataFim = '';

        $withFilter = false;

        $queryAcaos = Acao::find()
            ->joinWith(['eixo.planoIntegridade'])
            ->where(['plano_integridade_id' => $modelPlano->id]);

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
