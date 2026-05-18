<?php

namespace app\modules\admin\controllers;

use Yii;
use app\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\modules\admin\models\Orgao;
use app\modules\admin\models\pesquisa\OrgaoSearch;
use app\modules\admin\models\OrgaoContabil;
use app\modules\admin\models\InformacaoEstado;

/**
 * OrgaoController implements the CRUD actions for Orgao model.
 */
class OrgaoController extends Controller
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
     * Lists all Orgao models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('orgao-listar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new OrgaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orgao model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Universal::temPermissao('orgao-visualizar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $contabilQuery = OrgaoContabil::find()->where(['orgao_id' => $id]);
        $pages = new Pagination(['totalCount' => $contabilQuery->count(), 'defaultPageSize' => 3]);

        $modelsContabil = $contabilQuery->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy(['ano' => SORT_DESC])
            ->all();

        $responseInfEstado = InformacaoEstado::find()->all();

        $infsEstado = [];
        foreach ($responseInfEstado as $infEstado) {
            $infsEstado[$infEstado->ano] = $infEstado->orcamento;
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelsContabil' => $modelsContabil,
            'pages' => $pages,
            'infsEstado' => $infsEstado
        ]);
    }

    /**
     * Creates a new Orgao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('orgao-cadastrar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new Orgao();
        $modelsContabil = [new OrgaoContabil()];

        if ($model->load(Yii::$app->request->post())) {
            $modelsContabil = Model::createMultiple(OrgaoContabil::className());
            Model::loadMultiple($modelsContabil, Yii::$app->request->post());

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsContabil) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsContabil as $modelContabil) {
                            $modelContabil->orgao_id = $model->id;

                            if (!($flag = $modelContabil->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsContabil' => (empty($modelsContabil)) ? [new OrgaoContabil()] : $modelsContabil
        ]);
    }

    /**
     * Updates an existing Orgao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Universal::temPermissao('orgao-editar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = $this->findModel($id);
        $modelsContabil = $model->orgaoContabils;

        if ($model->load(Yii::$app->request->post())) {
            $oldIds = ArrayHelper::map($modelsContabil, 'id', 'id');
            $modelsContabil = Model::createMultiple(OrgaoContabil::className(), $modelsContabil);
            Model::loadMultiple($modelsContabil, Yii::$app->request->post());
            $deletedIds = array_diff($oldIds, array_filter(ArrayHelper::map($modelsContabil, 'id', 'id')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsContabil) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIds)) {
                            OrgaoContabil::deleteAll(['id' => $deletedIds]);
                        }

                        foreach ($modelsContabil as $modelContabil) {
                            $modelContabil->orgao_id = $model->id;

                            if (! ($flag = $modelContabil->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsContabil' => (empty($modelsContabil)) ? [new OrgaoContabil()] : $modelsContabil
        ]);
    }

    /**
     * Deletes an existing Orgao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Universal::temPermissao('orgao-apagar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $this->findModel($id)->delete();
        Universal::flash();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orgao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orgao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orgao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
