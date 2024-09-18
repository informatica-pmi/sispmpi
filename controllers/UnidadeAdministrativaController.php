<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\modules\admin\models\InformacaoEstado;
use app\modules\admin\models\OrgaoContabil;
use app\models\pesquisa\UnidadeAdministrativaSearch;

/**
 * UnidadeAdministrativaController implements the CRUD actions for UnidadeAdministrativa model.
 */
class UnidadeAdministrativaController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UnidadeAdministrativa models.
     *
     * @param integer|null $page
     * @param integer|null $id
     * @return mixed
     */
    public function actionIndex($page = 1, $id = '')
    {
        $model = empty($id) ? new UnidadeAdministrativa() : $this->findModel($id);

        $hasPermissionList = empty($id) && !Universal::temPermissao('unidade-administrativa-listar');

        $hasPermissionUpdate = !empty($id) && !Universal::temPermissao('unidade-administrativa-editar-orgao', $model);

        if ($hasPermissionList || $hasPermissionUpdate) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new UnidadeAdministrativaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $responseInfEstado = InformacaoEstado::findOne(['ano' => date('Y')]);
        $responseOrgaoContabil = OrgaoContabil::findOne(['orgao_id' => User::getIdentidade('orgao_id')]);

        if ($model->load(Yii::$app->request->post())) {
            $model->orgao_id = User::getIdentidade('orgao_id');

            if ($model->save()) {
                Universal::flash();

                return $this->redirect(['index',
                    'page' => $page
                ]);
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'responseInfEstado' => $responseInfEstado,
            'responseOrgaoContabil' => $responseOrgaoContabil,
            'page' => $page,
        ]);
    }

    /**
     * Deletes an existing UnidadeAdministrativa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!Universal::temPermissao('unidade-administrativa-apagar-orgao', $model)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model->delete();
        Universal::flash();
        return $this->redirect(['index']);
    }

    /**
     * Finds the UnidadeAdministrativa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UnidadeAdministrativa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UnidadeAdministrativa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
