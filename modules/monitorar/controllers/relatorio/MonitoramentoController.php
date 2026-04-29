<?php

namespace app\modules\monitorar\controllers\relatorio;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\Historico;
use app\models\pesquisa\AcaoSearch;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;

/**
 * Default controller for the `monitorar` module action `gerar relatorio of the acoes`
 */
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
     * Gera PDF com os detalhes de uma ação especifica
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

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
            return $this->redirect(['/site/acesso-negado']);
        }

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
