<?php

namespace app\modules\monitorar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Status;
use app\models\AcaoMonitoramentoRecomendacao;
use app\models\User;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * AcaoMonitoramentoController implements the CRUD actions for AcaoMonitoramento model.
 */
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
            ],
        ];
    }

    /**
     * Cria uma nova AcaoMonitoramento
     * Se a criação for um sucesso, o browser te redirecionará para a página 'acao/index'
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionCreate($acaoId)
    {
        $userId = User::getIdentidade('id');

        $modelAcao = Acao::findOne($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $acaoMonitoramento = $modelAcao->acaoMonitoramento;

        $modelAcaoMonitoramento = empty($acaoMonitoramento) ? new AcaoMonitoramento() : $acaoMonitoramento;

        $modelsAcaoMonitoramentoRecomendacao = $modelAcaoMonitoramento->acaoMonitoramentoRecomendacaos;

        $request = Yii::$app->request;

        if ($modelAcaoMonitoramento->load($request->post())) {
            $oldRecomendacaoIds = ArrayHelper::map($modelsAcaoMonitoramentoRecomendacao, 'id', 'id');
            $modelsAcaoMonitoramentoRecomendacao = Model::createMultiple(
                AcaoMonitoramentoRecomendacao::className(),
                $modelsAcaoMonitoramentoRecomendacao
            );
            Model::loadMultiple($modelsAcaoMonitoramentoRecomendacao, $request->post());
            $deletedRecomendacaoIds = array_diff(
                $oldRecomendacaoIds,
                array_filter(ArrayHelper::map($modelsAcaoMonitoramentoRecomendacao, 'id', 'id'))
            );

            $modelAcaoMonitoramento->acao_id = $modelAcao->id;
            $modelAcaoMonitoramento->usuario_id = $userId;

            $valid = $modelAcaoMonitoramento->validate();
            $valid = Model::validateMultiple($modelsAcaoMonitoramentoRecomendacao) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelAcaoMonitoramento->save(false)) {
                        if (!empty($deletedRecomendacaoIds)) {
                            AcaoMonitoramentoRecomendacao::deleteAll(['id' => $deletedRecomendacaoIds]);
                        }

                        $newRecomendacaos = array_filter(ArrayHelper::getColumn(
                            $modelsAcaoMonitoramentoRecomendacao,
                            function ($recomendacao) {
                                return $recomendacao->isNewRecord;
                            }
                        ));

                        $existNewRecomendacao = count($newRecomendacaos) != 0 ? true : false;

                        foreach ($modelsAcaoMonitoramentoRecomendacao as $modelAcaoMonitoramentoRecomendacao) {
                            $modelAcaoMonitoramentoRecomendacao->acao_monitoramento_id = $modelAcaoMonitoramento->id;
                            $modelAcaoMonitoramentoRecomendacao->usuario_id = $userId;

                            if (!($flag = $modelAcaoMonitoramentoRecomendacao->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();

                        $usersExecutor = User::find()
                            ->joinWith(['authAssignment'])
                            ->where([
                                'item_name' => User::PERFIL_EXECUTOR,
                                'orgao_id' => User::getIdentidade('orgao_id'),
                                'status' => Status::STATUS_ATIVO,
                            ])
                            ->all();

                        if ($usersExecutor && $existNewRecomendacao) {
                            Mail::sendMultiple(
                                './monitorar/executor-recomendacao',
                                ['tituloAcao' => $modelAcao->titulo],
                                $usersExecutor,
                                'Nova recomendação',
                            );
                        }

                        Universal::flash();
                        return $this->redirect(['@monitorar/acao/default/view', 'acaoId' => $modelAcao->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
    }
}
