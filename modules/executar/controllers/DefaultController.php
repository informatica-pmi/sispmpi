<?php

namespace app\modules\executar\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use ReflectionClass;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\models\pesquisa\AcaoSearch;
use app\models\Acao;
use app\models\Historico;
use app\models\Servidor;
use app\modules\executar\models\AcaoExecucao;
use app\modules\executar\models\AcaoServidor;

/**
 * Default controller for the `executar` module
 */
class DefaultController extends Controller
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
     * Página inicial do módulo executar
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

        if (!Universal::temPermissao('modulo-execucao')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if (!$modelPlano) {
            Universal::flash('error', 'Não foi encontrado nenhum plano de integridade publicado.');
            return $this->redirect(['/site/index']);
        }

        $searchModel = new AcaoSearch();

        $searchModel->plano_integridade_id = $modelPlano->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $paginationAcao = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $dataProvider->getTotalCount(),
        ]);

        $eixosIds = ArrayHelper::getColumn($modelPlano->eixos, 'id');

        $acaos = Acao::findAll(['eixo_id' => $eixosIds]);

        $acaosIds = ArrayHelper::getColumn($acaos, 'id');

        $acaoExecucaos = AcaoExecucao::findAll(['acao_id' => $acaosIds]);

        $acaoExecucaosIds = ArrayHelper::getColumn($acaoExecucaos, 'id');

        $servidors = AcaoServidor::findAll(['acao_id' => $acaosIds]);

        $servidorsIds = ArrayHelper::getColumn($servidors, 'servidor_id');

        $lastRegisterHistoric = Historico::find()
            ->where([
                'model' => Acao::tableName(),
                'id_registro' => $acaosIds,
            ])->orWhere([
                'model' => (new ReflectionClass(AcaoExecucao::className()))->getShortName(),
                'id_registro' => $acaoExecucaosIds,
            ])
            ->orWhere([
                'model' => (new ReflectionClass(Servidor::className()))->getShortName(),
                'id_registro' => $servidorsIds,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $dateCompletion = Universal::convertDate($modelPlano->publicacao->created_at);

        $lastDateModified = $lastRegisterHistoric ?
            Universal::convertDate($lastRegisterHistoric->created_at) :
            $dateCompletion;

        return $this->render('index', [
            'modelPlano' => $modelPlano,
            'userOrgaoNome' => $userOrgaoNome,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'paginationAcao' => $paginationAcao,
            'dateCompletion' => $dateCompletion,
            'lastDateModified' => $lastDateModified,
        ]);
    }
}
