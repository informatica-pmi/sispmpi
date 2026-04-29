<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\User;
use app\models\AuthAssignment;
use app\models\UnidadeAdministrativa;
use app\modules\admin\models\Orgao;

/**
 * UserOutroController implements the CRUD actions for User model.
 */
class UserOutroController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('usuario-cadastrar-outros')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new User();
        $model->scenario = User::SCENARIO_OUTRO;

        $modelAuthAssignment = new AuthAssignment();

        $isAdministrador = User::getPerfil() === User::PERFIL_ADMINISTRADOR;

        $optionsUnidadeAdministrativa = $isAdministrador ? Universal::getDropDown(UnidadeAdministrativa::className()) :
            ArrayHelper::map(
                UnidadeAdministrativa::find()
                    ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                    ->all(),
                'id',
                'nome'
            );

        $optionsOrgao = Universal::getDropDown(Orgao::className(), true);

        if ($model->load(Yii::$app->request->post())) {
            $senha = Yii::$app->security->generateRandomString(6);

            $model->cadastrado_por = User::getIdentidade('id');
            $model->generatePassword($senha);
            $model->orgao_id = !empty($model->orgao_id) ? $model->orgao_id : User::getIdentidade('orgao_id');

            if ($model->save()) {
                $modelAuthAssignment->item_name = $model->perfil;
                $modelAuthAssignment->user_id = $model->id;

                if ($modelAuthAssignment->save(false)) {
                    $mailParams = [
                        'nome' => $model->nome,
                        'login' => $model->login,
                        'senha' => $senha
                    ];

                    Mail::send('user', $mailParams, $model->email, 'Novo Usuário');

                    Universal::flash();
                    return $this->redirect(['/user/view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
            'optionsOrgao' => $optionsOrgao,
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
        $modelAuthAssignment = $model->authAssignment;

        $model->perfil = $modelAuthAssignment->item_name;

        if (!Universal::temPermissao('usuario-editar', $model)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model->scenario = User::SCENARIO_OUTRO;
        $oldSenha = $model->senha;
        $model->senha = '';

        $isAdministrador = User::getPerfil() === User::PERFIL_ADMINISTRADOR;

        $optionsUnidadeAdministrativa = $isAdministrador ? Universal::getDropDown(UnidadeAdministrativa::className()) :
            ArrayHelper::map(
                UnidadeAdministrativa::find()
                    ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                    ->all(),
                'id',
                'nome'
            );

        $optionsOrgao = Universal::getDropDown(Orgao::className(), true);

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->senha)) {
                $model->generatePassword($model->senha);
            } else {
                $model->senha = $oldSenha;
            }

            if ($model->perfil != User::PERFIL_EXECUTOR) {
                $model->unidade_administrativa_id = null;
            }

            $modelAuthAssignment->item_name = $model->perfil;
            if ($model->save() && $modelAuthAssignment->save(false)) {
                if ($model->id != User::getIdentidade('id')) {
                    $mailParams = ['nome' => $model->nome];

                    Mail::send('user-edit', $mailParams, $model->email, 'Alteração nos dados');
                }

                Universal::flash();
                return $this->redirect(['/user/view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
            'optionsOrgao' => $optionsOrgao,
        ]);
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
