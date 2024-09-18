<?php

namespace app\modules\auditor\controllers\relatorio;

use yii\web\Controller;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\pesquisa\AcaoSearch;
use app\models\PlanoIntegridade;
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
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionIndex($planoId)
    {
        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-auditor', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModelAcao = new AcaoSearch();

        $searchModelAcao->plano_integridade_id = $modelPlano->id;

        $dataProviderAcao = $searchModelAcao->search([]);

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
