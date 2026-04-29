<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\modules\admin\models\InformacaoEstado;
use app\modules\admin\models\pesquisa\InformacaoEstadoSearch;

/**
 * InformacaoEstadoController implements the CRUD actions for InformacaoEstado model.
 */
class InformacaoEstadoController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InformacaoEstado models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('informacao-estado-listar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new InformacaoEstadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InformacaoEstado model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Universal::temPermissao('informacao-estado-visualizar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new InformacaoEstado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('informacao-estado-cadastrar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new InformacaoEstado();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Universal::flash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InformacaoEstado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Universal::temPermissao('informacao-estado-editar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Universal::flash();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InformacaoEstado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Universal::temPermissao('informacao-estado-apagar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $this->findModel($id)->delete();
        Universal::flash();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InformacaoEstado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InformacaoEstado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InformacaoEstado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
