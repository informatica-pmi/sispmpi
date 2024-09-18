<?php

namespace app\modules\avaliar\controllers\reuniao;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\monitorar\models\pesquisa\ReuniaoSearch;

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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lista todas as reuniões
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new ReuniaoSearch();

        $searchModel->plano_integridade_id = $modelPlano->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $dataProvider->getTotalCount(),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
        ]);
    }
}
