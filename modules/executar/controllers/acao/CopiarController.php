<?php

namespace app\modules\executar\controllers\acao;

use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\AcaoMonitoramentoRecomendacao;
use app\modules\executar\models\AcaoExecucao;
use app\modules\executar\models\AcaoExecucaoArquivo;
use app\modules\executar\models\AcaoExecucaoFatorLimitante;
use app\modules\executar\models\AcaoServidor;
use app\modules\monitorar\models\AcaoMonitoramento;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class CopiarController extends Controller
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
     * Se a atualizacao for um sucesso, o browser te redirecionará para a página 'acao/view'
     *
     * @param int $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionUpdate($acaoId)
    {
        $modelAcao = Acao::findOne($acaoId);

        $modelAcaoReferencia = $modelAcao->acaoReferencia;

        $modelAcao->classificacao = $modelAcaoReferencia->classificacao;
        $modelAcao->previsao_inicio = $modelAcaoReferencia->previsao_inicio;
        $modelAcao->previsao_conclusao = $modelAcaoReferencia->previsao_conclusao;
        $modelAcao->orcamento_previsto = $modelAcaoReferencia->orcamento_previsto;
        $modelAcao->status = $modelAcaoReferencia->status;
        $modelAcao->acao_referencia_id = null;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($flag = $modelAcao->save()) {
                $acaoTipoItems = ArrayHelper::getColumn(
                    $modelAcaoReferencia->acaoTipos,
                    function ($acaoTipo) use ($modelAcao) {
                        return [
                            $modelAcao->id,
                            $acaoTipo['tipo_id']
                        ];
                    }
                );

                Yii::$app->db->createCommand()->batchInsert(
                    'acao_tipo',
                    ['acao_id', 'tipo_id'],
                    $acaoTipoItems
                )->execute();

                // acao_servidor
                $acaoServidors = $modelAcaoReferencia->acaoServidors;
                if ($acaoServidors) {
                    foreach ($acaoServidors as $acaoServidor) {
                        $newAcaoServidor = new AcaoServidor();
                        $newAcaoServidor->attributes = $acaoServidor->attributes;
                        $newAcaoServidor->acao_id = $modelAcao->id;

                        if (! ($flag = $newAcaoServidor->save())) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }

                // acao_execucao
                $acaoExecucao = $modelAcaoReferencia->acaoExecucao;
                if ($acaoExecucao) {
                    $newAcaoExecucao = new AcaoExecucao();
                    $newAcaoExecucao->attributes = $acaoExecucao->attributes;
                    $newAcaoExecucao->acao_id = $modelAcao->id;

                    if ($flag = $newAcaoExecucao->save()) {
                        // acao_execucao_arquivo
                        $acaoExecucaoArquivos = $acaoExecucao->acaoExecucaoArquivos;
                        if ($acaoExecucaoArquivos) {
                            foreach ($acaoExecucaoArquivos as $acaoExecucaoArquivo) {
                                $newAcaoExecucaoArquivo = new AcaoExecucaoArquivo();
                                $newAcaoExecucaoArquivo->arquivo_id = $acaoExecucaoArquivo->arquivo_id;
                                $newAcaoExecucaoArquivo->acao_execucao_id = $newAcaoExecucao->id;

                                if (! ($flag = $newAcaoExecucaoArquivo->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        // acao_execucao_fator_limitante
                        $acaoExecucaoFatorLimitantes = $acaoExecucao->acaoExecucaoFatorLimitantes;
                        if ($acaoExecucaoFatorLimitantes) {
                            foreach ($acaoExecucaoFatorLimitantes as $acaoExecucaoFatorLimitante) {
                                $newAcaoExecucaoFatorLimitante = new AcaoExecucaoFatorLimitante();
                                $newAcaoExecucaoFatorLimitante->fator_limitante_id = $acaoExecucaoFatorLimitante->fator_limitante_id;
                                $newAcaoExecucaoFatorLimitante->acao_execucao_id = $newAcaoExecucao->id;

                                if (! ($flag = $newAcaoExecucaoFatorLimitante->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                }

                // acao_monitoramento
                $acaoMonitoramento = $modelAcaoReferencia->acaoMonitoramento;
                if ($acaoMonitoramento) {
                    $newAcaoMonitoramento = new AcaoMonitoramento();
                    $newAcaoMonitoramento->attributes = $acaoMonitoramento->attributes;
                    $newAcaoMonitoramento->acao_id = $modelAcao->id;

                    if ($flag = $newAcaoMonitoramento->save()) {
                        $acaoMonitoramentoRecomendacaos = $acaoMonitoramento->acaoMonitoramentoRecomendacaos;

                        if ($acaoMonitoramentoRecomendacaos) {
                            foreach ($acaoMonitoramentoRecomendacaos as $acaoMonitoramentoRecomendacao) {
                                $newAcaoMonitoramentoRecomendacao = new AcaoMonitoramentoRecomendacao();
                                $newAcaoMonitoramentoRecomendacao->attributes = $acaoMonitoramentoRecomendacao->attributes;
                                $newAcaoMonitoramentoRecomendacao->acao_monitoramento_id = $newAcaoMonitoramento->id;

                                if (! ($flag = $newAcaoMonitoramentoRecomendacao->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                }

                // acao_avaliacao_recomendacao
                $acaoAvaliacaoRecomendacaos = $modelAcaoReferencia->acaoAvaliacaoRecomendacaos;
                if ($acaoAvaliacaoRecomendacaos) {
                    foreach ($acaoAvaliacaoRecomendacaos as $acaoAvaliacaoRecomendacao) {
                        $newAcaoAvaliacaoRecomendacao = new AcaoAvaliacaoRecomendacao();
                        $newAcaoAvaliacaoRecomendacao->attributes = $acaoAvaliacaoRecomendacao->attributes;
                        $newAcaoAvaliacaoRecomendacao->acao_id = $modelAcao->id;

                        if (! ($flag = $newAcaoAvaliacaoRecomendacao->save())) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    Universal::flash();
                    return $this->redirect(['@executar/acao/default/view', 'acaoId' => $modelAcao->id]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }
}
