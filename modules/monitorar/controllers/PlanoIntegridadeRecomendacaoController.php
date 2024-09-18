<?php

namespace app\modules\monitorar\controllers;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\PlanoIntegridadeRecomendacao;
use app\models\Status;
use app\models\User;

class PlanoIntegridadeRecomendacaoController extends Controller
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
     * Atualiza um ou varios PlanoIntegridadeRecomendacao
     * Se a atualizacao for um sucesso, o browser te redirecionará para a página 'monitorar'
     *
     * @return mixed
     */
    public function actionUpdate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userId = User::getIdentidade('id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsPlanoIntegridadeRecomendacao = $modelPlano->planoIntegridadeRecomendacaos;

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $modelsPlanoIntegridadeRecomendacao = Model::createMultiple(
                PlanoIntegridadeRecomendacao::className(),
                $modelsPlanoIntegridadeRecomendacao
            );

            Model::loadMultiple($modelsPlanoIntegridadeRecomendacao, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsPlanoIntegridadeRecomendacao);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsPlanoIntegridadeRecomendacao as $modelPlanoIntegridadeRecomendacao) {
                        if (
                            !empty($modelPlanoIntegridadeRecomendacao->resposta) &&
                            is_null($modelPlanoIntegridadeRecomendacao->usuario_resposta_id)
                        ) {
                            $modelPlanoIntegridadeRecomendacao->usuario_resposta_id = $userId;
                        }

                        $modelPlanoIntegridadeRecomendacao->plano_integridade_id = $modelPlano->id;

                        if (! ($flag = $modelPlanoIntegridadeRecomendacao->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['@monitorar']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelsPlanoIntegridadeRecomendacao' => (empty($modelsPlanoIntegridadeRecomendacao)) ?
                [new PlanoIntegridadeRecomendacao()] :
                $modelsPlanoIntegridadeRecomendacao
        ]);
    }
}
