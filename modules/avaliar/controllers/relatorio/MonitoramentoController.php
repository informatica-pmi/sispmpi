<?php

namespace app\modules\avaliar\controllers\relatorio;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\Historico;
use app\models\pesquisa\AcaoSearch;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;

class MonitoramentoController extends Controller
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
     * Gerar relatório de monitoramento
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $isObservador = User::getPerfil() === User::PERFIL_OBSERVADOR;

        $queryParamsHistoricoSearch = Yii::$app->request->getQueryParam('HistoricoSearch');

        $filterAcaoIds = $queryParamsHistoricoSearch['acaoIds'];

        $searchModelAcao = new AcaoSearch();

        $searchModelAcao->plano_integridade_id = $modelPlano->id;

        if (!empty($filterAcaoIds)) {
            $searchModelAcao->id = $filterAcaoIds;
        }

        $dataProviderAcao = $searchModelAcao->search(Yii::$app->request->queryParams);

        $dataProviderAcao->pagination->pageSize = $dataProviderAcao->getTotalCount();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'userOrgaoNome' => $userOrgaoNome,
                'modelsAcao' => $dataProviderAcao->getModels(),
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Relatório de monitoramento',
                'SetHeader' => [
                    'SISPMPI - Relatório de monitoramento||Gerado em: ' . date("d/m/Y") . ' às ' . date('H:i')
                ],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SISPMPI',
                'SetCreator' => 'SISPMPI',
            ]
        ]);

        return $pdf->render();
    }
}
