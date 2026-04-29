<?php

namespace app\modules\avaliar\controllers\promover;

use yii\filters\VerbFilter;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\avaliar\models\Promover;

/**
 * PromoverIntegridadeController implements the CRUD actions for PromoverIntegridade model.
 */
class PdfController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Gera relátorio de todas ou de um periodo especifico PromoverIntegridade
     *
     * @param string $data Data de início selecionada na filtragem
     * @param string $dataFim Data de término selecionada na filtragem
     * @return mixed
     */
    public function actionIndex($data = '', $dataFim = '')
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO]
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $queryPromover = Promover::find()
            ->where(['orgao_id' => $userOrgaoId]);

        $filterDates = [];

        if (!empty($data) && !empty($dataFim)) {
            $queryPromover->andWhere(['between', 'data', $data, $dataFim]);

            $filterDates = [
                'inicio' => $data,
                'termino' => $dataFim
            ];
        }

        $modelsPromover = $queryPromover->all();

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'modelsPromover' => $modelsPromover,
                'filterDates' => $filterDates
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Promoção da integridade',
                'SetHeader' => [
                    'SISPMPI - Promoção da integridade||Gerado em: ' .
                    date("d/m/Y") . ' às ' . date('H:i')
                ],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SisPMPI',
                'SetCreator' => 'SisPMPI',
            ]
        ]);

        return $pdf->render();
    }
}
