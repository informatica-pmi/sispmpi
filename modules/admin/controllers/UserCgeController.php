<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\components\helpers\Mail;
use app\models\User;
use app\models\AuthAssignment;
use app\models\Status;
use app\modules\admin\models\Orgao;

/**
 * UserCgeController implements the CRUD actions for User model.
 */
class UserCgeController extends Controller
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('usuario-cadastrar-cge')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new User();
        $model->scenario = User::SCENARIO_CGE;

        $userPerfis = [User::PERFIL_ADMINISTRADOR, User::PERFIL_OBSERVADOR];

        if ($model->load(Yii::$app->request->post())) {
            $model->orgao_id = Orgao::ORGAO_CGE;
            $senha = Yii::$app->security->generateRandomString(6);

            $model->cadastrado_por = User::getIdentidade('id');
            $model->generatePassword($senha);

            if ($model->save()) {
                foreach ($userPerfis as $userPerfil) {
                    $modelAuthAssignment = new AuthAssignment();
                    $modelAuthAssignment->item_name = $userPerfil;
                    $modelAuthAssignment->user_id = $model->id;
                    $modelAuthAssignment->active = $userPerfil === User::PERFIL_OBSERVADOR ? Status::STATUS_NAO : Status::STATUS_SIM;
                    $modelAuthAssignment->created_at = time();

                    if (! ($flag = $modelAuthAssignment->save(false))) {
                        break;
                    }
                }

                if ($flag) {
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
        ]);
    }
}
