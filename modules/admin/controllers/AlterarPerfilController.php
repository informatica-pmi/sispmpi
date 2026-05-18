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
use app\modules\admin\models\AlterarPerfil;

class AlterarPerfilController extends Controller
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

    public function actionUpdate($to)
    {
        $userPerfis = ArrayHelper::getColumn(Yii::$app->user->identity->authAssignments, 'item_name');

        if (!ArrayHelper::isIn(User::PERFIL_ADMINISTRADOR, $userPerfis)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $model = new AlterarPerfil();

        $optionsOrgao = ArrayHelper::map(
            Orgao::find()
                ->innerJoinWith(['planoIntegridades'])
                ->where(['orgao.status' => Status::STATUS_ATIVO])
                ->all(),
        'id',
        'nome'
        );

        $user = User::findOne(User::getIdentidade('id'));

        /** @var AuthAssignment $modelPerfilAnterior */
        $modelPerfilAnterior = User::getIdentidade('authAssignment');

        $modelPerfilAtual = AuthAssignment::findOne(['user_id' => $user->id, 'item_name' => $to]);

        $transaction = Yii::$app->db->beginTransaction();

        if ($to === User::PERFIL_ADMINISTRADOR) {
            $flag = $this->updateProfiles(
                $user,
                Orgao::ORGAO_CGE,
                $modelPerfilAnterior,
                $modelPerfilAtual,
                $transaction
            );
        }

        if ($model->load(Yii::$app->request->post())) {
            $flag = $this->updateProfiles(
                $user,
                $model->orgao_id,
                $modelPerfilAnterior,
                $modelPerfilAtual,
                $transaction
            );
        }

        if (isset($flag) && $flag) {
            $transaction->commit();

            Universal::flash('success', 'Perfil alterado com sucesso.');

            return $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render('update', [
            'model' => $model,
            'optionsOrgao' => $optionsOrgao,
            'to' => $to,
        ]);
    }

    private function updateProfiles($user, $orgaoId, $modelPerfilAnterior, $modelPerfilAtual, $transaction): bool
    {
        $user->orgao_id = $orgaoId;

        if (! ($user->save())) {
            $transaction->rollBack();
            return false;
        }

        if ($modelPerfilAnterior->item_name !== $modelPerfilAtual->item_name) {
            $modelPerfilAnterior->active = Status::STATUS_NAO;

            if (! ($modelPerfilAnterior->save())) {
                $transaction->rollBack();
                return false;
            }

            $modelPerfilAtual->active = Status::STATUS_SIM;

            if (! ($modelPerfilAtual->save())) {
                $transaction->rollBack();
                return false;
            }
        }

        return true;
    }
}
