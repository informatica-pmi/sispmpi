<?php

namespace app\modules\monitorar\controllers\reuniao;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\modules\monitorar\models\Reuniao;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;

/**
 * PdfController implements the export register Reuniao model.
 */
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
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Gera PDf com detalhes de uma reunião existente
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

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
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
