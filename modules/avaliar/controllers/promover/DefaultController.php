<?php

namespace app\modules\avaliar\controllers\promover;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\avaliar\models\pesquisa\PromoverSearch;
use app\modules\avaliar\models\Promover;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Promover model.
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
     * Lista todas as promover integridade
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO]
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new PromoverSearch();

        $searchModel->orgao_id = $userOrgaoId;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagination = new Pagination([
            'pageSize' => 10,
            'totalCount' => $dataProvider->getTotalCount()
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Cria uma nova PromoverIntegridade
     * Se a criação é um sucesso, o browser te redirecionará para a index
     * @return mixed
     */
    public function actionCreate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPromover = new Promover();

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO]
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if ($modelPromover->load(Yii::$app->request->post())) {
            $modelPromover->plano_integridade_id = $modelPlano->id;
            $modelPromover->orgao_id = $userOrgaoId;

            if ($modelPromover->save()) {
                Universal::flash();
                return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('create', [
            'modelPromover' => $modelPromover,
        ]);
    }

    /**
     * Updates an existing Promover model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPromover = $this->findModel($id);

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO]
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if ($modelPromover->load(Yii::$app->request->post()) && $modelPromover->save()) {
            Universal::flash();
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'modelPromover' => $modelPromover,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promover the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promover::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
