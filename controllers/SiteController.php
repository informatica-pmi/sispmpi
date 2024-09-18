<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use app\components\helpers\Universal;
use app\models\LoginForm;
use app\models\PlanoIntegridade;
use app\models\User;
use app\models\Status;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\UnidadeAdministrativa;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'acesso-negado', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'acesso-negado', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'clear',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Index action, this is homepage of system.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $unidadeExists = UnidadeAdministrativa::find()
            ->where(['orgao_id' => User::getIdentidade('orgao_id')])
            ->count();

        $planoExists = PlanoIntegridade::find()
            ->joinWith(['publicacao'])
            ->where(['orgao_id' => User::getIdentidade('orgao_id')])
            ->andWhere(['not', ['publicacao.plano_acao_arquivo' => null]])
            ->count();

        $isAuditor = User::getPerfil() === User::PERFIL_AUDITOR;
        $isMonitoramento = User::getPerfil() === User::PERFIL_MONITORAMENTO;

        $monitoramentoExists = User::find()
            ->joinWith(['authAssignment'])
            ->where([
                'item_name' => User::PERFIL_MONITORAMENTO,
                'orgao_id' => User::getIdentidade('orgao_id'),
                'status' => Status::STATUS_ATIVO
            ])
            ->count();

        $executorExists = User::find()
            ->joinWith(['authAssignment'])
            ->where([
                'item_name' => User::PERFIL_EXECUTOR,
                'orgao_id' => User::getIdentidade('orgao_id'),
                'status' => Status::STATUS_ATIVO
            ])
            ->count();

        $alertShowAuditor = $planoExists && !$monitoramentoExists && $isAuditor;
        $alertShowMonitoramento = $planoExists && !$executorExists && $isMonitoramento;
        $alertShowAuditorUnidade = !$unidadeExists && $isAuditor;

        return $this->render('index', [
            'alertShowAuditor' => $alertShowAuditor,
            'alertShowMonitoramento' => $alertShowMonitoramento,
            'alertShowAuditorUnidade' => $alertShowAuditorUnidade,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'clear.php';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (User::getIdentidade('status') === Status::STATUS_INATIVO) {
                Yii::$app->user->logout();

                Universal::flash(
                    'error',
                    'Usuário ou senha incorretos.'
                );

                return $this->goHome();
            } elseif (empty(User::getIdentidade('masp'))) {
                return $this->redirect(['/user/update', 'id' => User::getIdentidade('id')]);
            }

            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        $this->layout = 'clear.php';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Universal::flash('success', 'Verifique seu e-mail para mais instruções.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    'Desculpe, não podemos redefinir a senha para o endereço de e-mail fornecido.'
                );
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'clear';

        try {
            $model = new ResetPasswordForm($token);
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Nova senha salva.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Acesso negado action
     *
     * @return mixed
     */
    public function actionAcessoNegado()
    {
        $this->layout = 'clear';

        $nomeArray = explode(" ", User::getIdentidade('nome'));

        return $this->render('acesso-negado', [
            'nome' => $nomeArray[0]
        ]);
    }
}
