<?php

namespace app\modules\avaliar\controllers;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
     * Cria uma nova PlanoIntegridadeRecomendacao
     * Se a criação for um sucesso, o browser te redirecionará para a pagina de 'update'
     * @return mixed
     */
    public function actionCreate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userId = User::getIdentidade('id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $models = [new PlanoIntegridadeRecomendacao()];

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $models = Model::createMultiple(PlanoIntegridadeRecomendacao::className());

            Model::loadMultiple($models, Yii::$app->request->post());

            $valid = Model::validateMultiple($models);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $modelsReversed = array_reverse($models);

                    foreach ($modelsReversed as $model) {
                        $model->plano_integridade_id = $modelPlano->id;
                        $model->usuario_id = $userId;

                        if (! ($flag = $model->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['update']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'models' => (empty($models)) ? [new PlanoIntegridadeRecomendacao()] : $models
        ]);
    }

    /**
     * Atualizando PlanoIntegriadadeRecomendacao
     * Se a atualização for um sucesso, o browser te redirecionará para a página de 'update'
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

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $models = $modelPlano->planoIntegridadeRecomendacaos;

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $oldIds = ArrayHelper::map($models, 'id', 'id');

            $models = Model::createMultiple(PlanoIntegridadeRecomendacao::className(), $models);

            Model::loadMultiple($models, Yii::$app->request->post());

            $deletedIds = array_diff(
                $oldIds,
                array_filter(ArrayHelper::map($models, 'id', 'id'))
            );

            $valid = Model::validateMultiple($models);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!empty($deletedIds)) {
                        PlanoIntegridadeRecomendacao::deleteAll(['id' => $deletedIds]);
                    }

                    $modelsReversed = array_reverse($models);

                    foreach ($modelsReversed as $model) {
                        $model->plano_integridade_id = $modelPlano->id;
                        $model->usuario_id = $userId;

                        if (! ($flag = $model->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['update']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'models' => (empty($models)) ? [new PlanoIntegridadeRecomendacao()] : $models
        ]);
    }
}
