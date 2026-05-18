<?php

namespace app\modules\avaliar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\base\Model;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\Status;
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
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Cria uma nova AcaoAvaliacaoRecomendacao
     * Se a criação for um sucesso, o browser te redirecionará para a view.
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionCreate($acaoId)
    {
        $userId = User::getIdentidade('id');

        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelAcao = $this->findModel($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsAcaoAvaliacaoRecomendacao = $modelAcao->acaoAvaliacaoRecomendacaos;

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $oldIds = ArrayHelper::map($modelsAcaoAvaliacaoRecomendacao, 'id', 'id');

            $modelsAcaoAvaliacaoRecomendacao = Model::createMultiple(
                AcaoAvaliacaoRecomendacao::className(),
                $modelsAcaoAvaliacaoRecomendacao
            );

            Model::loadMultiple($modelsAcaoAvaliacaoRecomendacao, Yii::$app->request->post());

            $deletedIds = array_diff(
                $oldIds,
                array_filter(ArrayHelper::map($modelsAcaoAvaliacaoRecomendacao, 'id', 'id'))
            );

            $valid = Model::validateMultiple($modelsAcaoAvaliacaoRecomendacao);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!empty($deletedIds)) {
                        AcaoAvaliacaoRecomendacao::deleteAll(['id' => $deletedIds]);
                    }

                    $existsNewRecomendacao = false;

                    foreach ($modelsAcaoAvaliacaoRecomendacao as $modelAcaoAvaliacaoRecomendacao) {
                        if ((!$existsNewRecomendacao) && $modelAcaoAvaliacaoRecomendacao->isNewRecord) {
                            $existsNewRecomendacao = true;
                        }

                        $modelAcaoAvaliacaoRecomendacao->acao_id = $modelAcao->id;
                        $modelAcaoAvaliacaoRecomendacao->usuario_id = $userId;

                        if (! ($flag = $modelAcaoAvaliacaoRecomendacao->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();

                        if ($existsNewRecomendacao) {
                            $modelUsersReceivedMail = User::find()
                                ->joinWith(['authAssignment'])
                                ->where([
                                    'status' => Status::STATUS_ATIVO,
                                    'orgao_id' => $userOrgaoId,
                                ])
                                ->andWhere([
                                    'in',
                                    'auth_assignment.item_name',
                                    [User::PERFIL_EXECUTOR, User::PERFIL_MONITORAMENTO]
                                ])
                                ->all();

                            if ($modelUsersReceivedMail) {
                                Mail::sendMultiple(
                                    './avaliar/nova-recomendacao',
                                    ['tituloAcao' => $modelAcao->titulo],
                                    $modelUsersReceivedMail,
                                    'Nova Recomendação'
                                );
                            }
                        }

                        Universal::flash();
                        return $this->redirect(['@avaliar/acao/default/view', 'acaoId' => $modelAcao->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * Finds the Acao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Acao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Acao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
