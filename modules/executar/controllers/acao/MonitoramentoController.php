<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoMonitoramentoRecomendacao;
use app\models\User;

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
     * Atualiza uma Acao existente
     * Se a atualização for um sucesso, o browser te redirecionará para a pagina 'acao/view'
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionUpdate($acaoId)
    {
        $modelAcao = Acao::findOne($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $acaoMonitoramento = $modelAcao->acaoMonitoramento;

        $acaoMonitoramentoRecomendacaos = $acaoMonitoramento->acaoMonitoramentoRecomendacaos;

        $modelsAcaoMonitoramentoRecomendacao = empty($acaoMonitoramentoRecomendacaos) ?
            [new AcaoMonitoramentoRecomendacao()] :
            $acaoMonitoramentoRecomendacaos;

        $modelsAcaoMonitoramentoRecomendacao = Model::createMultiple(
            AcaoMonitoramentoRecomendacao::className(),
            $modelsAcaoMonitoramentoRecomendacao
        );

        if (Model::loadMultiple($modelsAcaoMonitoramentoRecomendacao, Yii::$app->request->post())) {
            $valid = Model::validateMultiple($modelsAcaoMonitoramentoRecomendacao);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsAcaoMonitoramentoRecomendacao as $modelAcaoMonitoramentoRecomendacao) {
                        if (
                            !empty($modelAcaoMonitoramentoRecomendacao->resposta) &&
                            is_null($modelAcaoMonitoramentoRecomendacao->usuario_resposta_id)
                        ) {
                            $modelAcaoMonitoramentoRecomendacao->usuario_resposta_id = User::getIdentidade('id');
                        }

                        if (!($flag = $modelAcaoMonitoramentoRecomendacao->save(false))) {
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
                    var_dump($e->getMessage());
                    $transaction->rollBack();
                }
            }
        }

        Universal::flash('error', 'Nenhuma recomendação cadastrada.');
        return $this->redirect(['@executar/acao/default/view', 'id' => $modelAcao->id]);
    }
}
