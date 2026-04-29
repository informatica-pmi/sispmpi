<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\User;

class AvaliacaoController extends Controller
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
            ]
        ];
    }

    /**
     * Atualiza uma ou varias AcaoAvaliacaoRecomendacao
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionUpdate($acaoId)
    {
        $userId = User::getIdentidade('id');

        $modelAcao = Acao::findOne($acaoId);

        $modelsAcaoAvaliacaoRecomendacao = $modelAcao->acaoAvaliacaoRecomendacaos;

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $modelsAcaoAvaliacaoRecomendacao = Model::createMultiple(
                AcaoAvaliacaoRecomendacao::className(),
                $modelsAcaoAvaliacaoRecomendacao
            );

            Model::loadMultiple($modelsAcaoAvaliacaoRecomendacao, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsAcaoAvaliacaoRecomendacao);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsAcaoAvaliacaoRecomendacao as $modelAcaoAvaliacaoRecomendacao) {
                        $modelAcaoAvaliacaoRecomendacao->usuario_resposta_id = $userId;

                        if (! ($flag = $modelAcaoAvaliacaoRecomendacao->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['@executar/acao/default/view', 'acaoId' => $modelAcao->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
    }
}
