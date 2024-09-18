<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\User;

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
     * Gera um PDF com os detalhes da acao selecionada
     *
     * @param int $acaoId identificador da acao
     * @return mixed
     */
    public function actionIndex($acaoId)
    {
        $modelAcao = $this->findModel($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        Yii::$app->formatter->nullDisplay = 'Conteúdo não informado no Plano de Integridade do órgão';

        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelAcao->tipoIds = ArrayHelper::getColumn($modelAcao->acaoTipos, 'tipo.nome');

        $modelAcao->previsao_inicio = Universal::convertDate($modelAcao->previsao_inicio);

        $modelAcao->previsao_conclusao = Universal::convertDate($modelAcao->previsao_conclusao);

        $modelAcao->unidadeApoioIds = ArrayHelper::getColumn(
            $modelAcao->acaoUnidadeApoios,
            'unidadeAdministrativa.nome'
        );

        $servidorResponsavel = '';

        if ($modelAcao->acaoServidorResponsavel) {
            $modelServidorResponsavel = $modelAcao->acaoServidorResponsavel->servidor;

            $servidorResponsavel = $modelServidorResponsavel->nome
                . ' / ' . $modelServidorResponsavel->masp_matricula
                . ' - ' . $modelServidorResponsavel->unidadeAdministrativa->nome ;
        }

        $servidorsEnvolvido = ArrayHelper::getColumn($modelAcao->acaoServidorsEnvolvido, 'servidor');

        $servidorsEnvolvido = $servidorsEnvolvido ?
            $servidorsEnvolvido :
            'Conteúdo não informado no Plano de Integridade do órgão';

        $modelAcaoExecucao = $modelAcao->acaoExecucao;

        if ($modelAcaoExecucao) {
            $modelAcaoExecucao->data_inicio = Universal::convertDate($modelAcaoExecucao->data_inicio);

            $modelAcaoExecucao->data_conclusao = Universal::convertDate($modelAcaoExecucao->data_conclusao);

            $modelAcaoExecucao->fatoresLimitantesIds = ArrayHelper::getColumn(
                $modelAcaoExecucao->acaoExecucaoFatorLimitantes,
                'fatorLimitante.nome'
            );
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'userOrgaoNome' => $userOrgaoNome,
                'modelAcao' => $modelAcao,
                'modelAcaoExecucao' => $modelAcaoExecucao,
                'servidorResponsavel' => $servidorResponsavel,
                'servidorsEnvolvido' => $servidorsEnvolvido,
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Execução das ações do plano de integridade',
                'SetHeader' => ['SISPMPI||Gerado em: ' . date("d/m/Y") . ' às ' . date('H:i')],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SisPMPI',
                'SetCreator' => 'SisPMPI',
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Finds the Acao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Acao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Acao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
