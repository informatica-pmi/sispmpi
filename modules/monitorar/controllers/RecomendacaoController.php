<?php

namespace app\modules\monitorar\controllers;

use Yii;
use app\models\Acao;
use app\models\User;
use yii\helpers\Url;
use app\models\Status;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Historico;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\PlanoIntegridade;
use app\models\pesquisa\AcaoSearch;
use app\modules\admin\models\Orgao;
use app\components\helpers\Universal;

/**
 * Recomendacao controller for the `monitorar` module
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
     * Renders the index view for the module
     * @param null|integer $filter Utilizado para realizar o filtro das ações
     * @return string
     */
    public function actionIndex($filter = null)
    {
        $modelOrgao = Orgao::findOne(User::getIdentidade('orgao_id'));

        $userOrgaoNome = $modelOrgao->nome;

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $modelOrgao->id,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO]
        ]);

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        $conditionElaboracao = $modelPlano->status == Status::PLANO_ELABORACAO && !$isObservador;

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $conditionElaboracao) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new AcaoSearch();

        $searchModel->plano_integridade_id = $modelPlano->id;

        if (!is_null($filter)) {
            $searchModel->filter = $filter;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagination = new Pagination([
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

        if ($modelPlano->status != Status::PLANO_PUBLICADO) {
            $dateCompletion = 'Não se aplica/Não definido';
        } else {
            $dateCompletion = Universal::convertDate($modelPlano->publicacao->created_at);
        }

        $lastDateModified = $lastRegisterHistoric ?
            Universal::convertDate($lastRegisterHistoric->created_at) :
            $dateCompletion;

        Url::remember([
            '@monitorar/recomendacao/index',
            'filter' => $searchModel->filter
        ], 'monitorar-recomendacao');

        return $this->render('index', [
            'modelPlano' => $modelPlano,
            'userOrgaoNome' => $userOrgaoNome,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
            'dateCompletion' => $dateCompletion,
            'lastDateModified' => $lastDateModified,
            'isObservador' => $isObservador,
        ]);
    }
}
