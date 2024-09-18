<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\Stakeholder;
use app\models\pesquisa\StakeholderSearch;

/**
 * StakeholderController implements the CRUD actions for Stakeholder model.
 */
class StakeholderController extends Controller
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
     * Lists all Stakeholder models.
     *
     * @param integer $page;
     * @param integer|string $id;
     * @return mixed
     */
    public function actionIndex($page = 1, $id = '')
    {
        $model = empty($id) ? new Stakeholder() : $this->findModel($id);

        if (!Universal::temPermissao('stakeholder-listar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new StakeholderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Universal::flash();

            return $this->redirect(['index',
                'page' => $page,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'page' => $page,
        ]);
    }

    /**
     * Deletes an existing Stakeholder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Universal::temPermissao('stakeholder-apagar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $this->findModel($id)->delete();
        Universal::flash();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Stakeholder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stakeholder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stakeholder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
