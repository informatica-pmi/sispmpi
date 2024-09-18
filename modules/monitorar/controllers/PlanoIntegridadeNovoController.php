<?php

namespace app\modules\monitorar\controllers;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\PlanoIntegridadeNovo;
use app\models\Status;
use app\models\User;

class PlanoIntegridadeNovoController extends Controller
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
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $plano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-monitoramento', $plano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        return $this->renderAjax('index');
    }

    /**
     * @param integer $tipoId Número identificador da solicitação, identificadores descritos no model "solicitacao"
     * @return mixed
     */
    public function actionCreate($tipoId)
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userId = User::getIdentidade('id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $tipoSolicitacao = PlanoIntegridadeNovo::getTipo($tipoId);

        $modelPlanoIntegridadeNovo = new PlanoIntegridadeNovo();

        $modelPlanoIntegridadeNovo->plano_integridade_id = $modelPlano->id;
        $modelPlanoIntegridadeNovo->tipo = $tipoId;
        $modelPlanoIntegridadeNovo->usuario_solicitante_id = $userId;

        $modelPlanoIntegridadeNovo->validate();

        if ($modelPlanoIntegridadeNovo->save()) {
            $usersAltaMonitoramento = User::find()
                ->joinWith(['authAssignment'])
                ->where([
                    'orgao_id' => $userOrgaoId,
                    'status' => Status::STATUS_ATIVO,
                ])
                ->andWhere(['in', 'item_name', [User::PERFIL_ALTA_ADMINISTRACAO, User::PERFIL_MONITORAMENTO]])
                ->all();

            if ($usersAltaMonitoramento) {
                Mail::sendMultiple(
                    './monitorar/alta-monitoramento-atualizacao',
                    ['tipoSolicitacao' => $tipoSolicitacao],
                    $usersAltaMonitoramento,
                    'Atualização do plano de integridade',
                );
            }

            $usersAuditor = User::find()
                ->joinWith(['authAssignment'])
                ->where([
                    'item_name' => User::PERFIL_AUDITOR,
                    'orgao_id' => $userOrgaoId,
                    'status' => Status::STATUS_ATIVO,
                ])
                ->all();

            if ($usersAuditor) {
                Mail::sendMultiple(
                    './monitorar/auditor-atualizacao',
                    ['tipoSolicitacao' => $tipoSolicitacao],
                    $usersAuditor,
                    'Atualização do plano de integridade',
                );
            }

            Universal::flash();
            return $this->redirect(['@monitorar']);
        }
    }
}
