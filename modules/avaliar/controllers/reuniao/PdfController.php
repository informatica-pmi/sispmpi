<?php

namespace app\modules\avaliar\controllers\reuniao;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\monitorar\models\Reuniao;

class PdfController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Gerar PDF com os detalhes da reunião
     *
     * @param integer $reuniaoId Número identificador da reuniao
     * @return mixed
     */
    public function actionIndex($reuniaoId)
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelReuniao = $this->findModel($reuniaoId);

        $modelReuniao->data = Universal::convertDate($modelReuniao->data);

        $servidorsNome = implode(', ', ArrayHelper::getColumn($modelReuniao->reuniaoServidors, 'servidor.nome'));

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'modelReuniao' => $modelReuniao,
                'servidorsNome' => $servidorsNome,
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Reunião do comitê de monitoramento',
                'SetHeader' => [
                    'SISPMPI - Reuniões do comitê de monitoramento||Gerado em: ' .
                    date("d/m/Y") . ' às ' . date('H:i')
                ],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SisPMPI',
                'SetCreator' => 'SisPMPI',
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Finds the Reuniao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reuniao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reuniao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
