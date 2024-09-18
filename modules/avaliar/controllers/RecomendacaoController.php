<?php

namespace app\modules\avaliar\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Historico;
use app\models\pesquisa\AcaoSearch;
use app\models\PlanoIntegridade;
use app\models\User;
use app\models\Status;

/**
 * Recomendacao controller for the `avalilar` module
 */
class RecomendacaoController extends Controller
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
     * Lista todas as ações
     *
     * @param null|integer $filter Utilizado para realizar o filtro das ações
     * @return mixed
     */
    public function actionIndex($filter = null)
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

        $searchModel = new AcaoSearch();

        $searchModel->plano_integridade_id = $modelPlano->id;

        if (!is_null($filter)) {
            $searchModel->filter = $filter;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $paginationAcao = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $dataProvider->getTotalCount(),
        ]);

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

        Url::remember([
            '@avaliar/recomendacao/index',
            'filter' => $searchModel->filter
        ], 'avaliar-recomendacao');

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
