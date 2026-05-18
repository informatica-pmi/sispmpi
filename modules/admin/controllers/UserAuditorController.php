<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\components\helpers\Mail;
use app\models\User;
use app\models\AuthAssignment;

/**
 * UserCgeController implements the CRUD actions for User model.
 */
class UserAuditorController extends Controller
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
        if (!Universal::temPermissao('usuario-cadastrar-auditor')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new User();
        $model->scenario = User::SCENARIO_AUDITOR;

        $modelAuthAssignment = new AuthAssignment();

        if ($model->load(Yii::$app->request->post())) {
            $senha = Yii::$app->security->generateRandomString(6);

            $model->cadastrado_por = User::getIdentidade('id');
            $model->generatePassword($senha);

            if ($model->save()) {
                $modelAuthAssignment->item_name = User::PERFIL_AUDITOR;
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
        ]);
    }
}
