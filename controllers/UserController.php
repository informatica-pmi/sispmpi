<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\User;
use app\models\pesquisa\UserSearch;
use app\components\helpers\Mail;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('usuario-listar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (!Universal::temPermissao('usuario-visualizar', $model)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('usuario-cadastrar')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $redirectHomePage = empty(User::getIdentidade('masp'));

        if (!Universal::temPermissao('usuario-editar', $model)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model->scenario = User::getPerfil() === User::PERFIL_TI ? User::SCENARIO_DEFAULT : User::SCENARIO_UPDATE;
        $oldSenha = $model->senha;
        $model->senha = '';

        $model->perfil = $model->authAssignment->item_name;

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->senha)) {
                $model->generatePassword($model->senha);
            } else {
                $model->senha = $oldSenha;
            }

            if ($model->save()) {
                if ($model->id != User::getIdentidade('id')) {
                    $mailParams = ['nome' => $model->nome];

                    Mail::send('user-edit', $mailParams, $model->email, 'Alteração nos dados');
                }

                Universal::flash();
                return $redirectHomePage ?
                    $this->redirect(Yii::$app->homeUrl) :
                    $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!Universal::temPermissao('usuario-apagar', $model)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
