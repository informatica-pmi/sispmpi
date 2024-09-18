<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\Status;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\AuthAssignment;
use yii\filters\AccessControl;
use app\modules\admin\models\Orgao;
use app\components\helpers\Universal;
use app\modules\admin\models\Monitoramento;

class MonitoramentoController extends Controller
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
            ]
        ];
    }

    public function actionUpdate($toAdmin = false)
    {
        $userPerfis = ArrayHelper::getColumn(Yii::$app->user->identity->authAssignments, 'item_name');

        if (!ArrayHelper::isIn(User::PERFIL_ADMINISTRADOR, $userPerfis)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new Monitoramento();

        $modelsOrgao = Orgao::find()
            ->innerJoinWith(['planoIntegridades'])
            ->where(['orgao.status' => Status::STATUS_ATIVO])
            ->all();

        $orgaoIds = ArrayHelper::getColumn($modelsOrgao, 'id');

        $optionsOrgao = ArrayHelper::map(Orgao::find()->where(['in', 'id', $orgaoIds])->all(), 'id', 'nome');

        $userId = User::getIdentidade('id');

        $modelAuthAssingmentAdministrador = AuthAssignment::findOne([
            'item_name' => User::PERFIL_ADMINISTRADOR,
            'user_id' => $userId
        ]);

        $modelAuthAssingmentObservador = AuthAssignment::findOne([
            'item_name' => User::PERFIL_OBSERVADOR,
            'user_id' => $userId
        ]);

        if ($toAdmin) {
            $modelAuthAssingmentAdministrador->active = Status::STATUS_SIM;
            $modelAuthAssingmentAdministrador->update();

            $modelAuthAssingmentObservador->active = Status::STATUS_NAO;
            $modelAuthAssingmentObservador->update();

            $flag = true;
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->set('observador_orgao_id', $model->orgao_id);

            $modelAuthAssingmentAdministrador->active = Status::STATUS_NAO;
            $modelAuthAssingmentAdministrador->update();

            $modelAuthAssingmentObservador->active = Status::STATUS_SIM;
            $modelAuthAssingmentObservador->update();

            $flag = true;
        }

        if (isset($flag) && $flag) {
            Universal::flash('success', 'Perfil alterado com sucesso.');

            return $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render('update', [
            'model' => $model,
            'optionsOrgao' => $optionsOrgao
        ]);
    }
}
