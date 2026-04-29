<?php

namespace app\modules\executar\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use kartik\mpdf\Pdf;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Historico;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\models\pesquisa\AcaoSearch;

/**
 * PdfController for the `generate` pdf home executar module
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
     * Gera um PDF com os detalhes de todas as ações
     * @return string
     */
    public function actionIndex()
    {
        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if ($modelPlano && Universal::temPermissao('modulo-execucao')) {
            $searchModelAcao = new AcaoSearch();

            $searchModelAcao->plano_integridade_id = $modelPlano->id;

            $dataProviderAcao = $searchModelAcao->search(Yii::$app->request->queryParams);

            $dataProviderAcao->pagination->pageSize = $dataProviderAcao->getTotalCount();

            $eixosIds = ArrayHelper::getColumn($modelPlano->eixos, 'id');

            $acaos = Acao::findAll(['eixo_id' => $eixosIds]);

            $acaosIds = ArrayHelper::getColumn($acaos, 'id');

            $lastRegisterHistoric = Historico::find()
                ->where([
                    'model' => Acao::tableName(),
                    'id_registro' => $acaosIds,
                ])
                ->orderBy(['id' => SORT_DESC])
                ->one();

            $dateCompletion = Universal::convertDate($modelPlano->publicacao->created_at);

            $lastDateModified = $lastRegisterHistoric ?
                Universal::convertDate($lastRegisterHistoric->created_at) :
                $dateCompletion;
        } else {
            return 'Error';
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial('index', [
                'userOrgaoNome' => $userOrgaoNome,
                'searchModelAcao' => $searchModelAcao,
                'modelsAcao' => $dataProviderAcao->getModels(),
                'dateCompletion' => $dateCompletion,
                'lastDateModified' => $lastDateModified,
            ]),
            'cssFile' => '@webroot/css/pdf.css',
            'methods' => [
                'SetTitle' => 'Execução das ações do plano de integridade',
                'SetHeader' => ['SISPMPI||Gerado em: ' . date("d/m/Y") . ' às ' . date('H:i')],
                'SetFooter' => ['|Página {PAGENO}|'],
                'SetAuthor' => 'SISPMPI',
                'SetCreator' => 'SISPMPI',
            ]
        ]);

        return $pdf->render();
    }
}
