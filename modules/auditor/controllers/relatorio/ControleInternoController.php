<?php

namespace app\modules\auditor\controllers\relatorio;

use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\PlanoIntegridadeRecomendacao;
use app\models\Status;
use app\models\User;

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

        $modelsPlanoIntegridadeRecomendacao = PlanoIntegridadeRecomendacao::find()
            ->where(['plano_integridade_id' => $modelPlano->id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'userOrgaoNome' => $userOrgaoNome,
                'modelsPlanoIntegridadeRecomendacao' => $modelsPlanoIntegridadeRecomendacao,
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
