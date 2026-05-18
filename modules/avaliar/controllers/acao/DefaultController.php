<?php

namespace app\modules\avaliar\controllers\acao;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\User;
use app\modules\executar\models\AcaoExecucao;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * Default controller for the `monitorar` module
 */
class DefaultController extends Controller
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
     * Visualizar detalhes da ação selecionada
     *
     * @param integer $acaoId Número identificador da ação
     * @return string
     */
    public function actionView($acaoId)
    {
        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelAcao = $this->findModel($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $tipoNomes = ArrayHelper::getColumn($modelAcao->acaoTipos, 'tipo.nome');

        $modelAcao->previsao_inicio = $modelAcao->previsao_inicio ?
            Universal::convertDate($modelAcao->previsao_inicio) :
            '';

        $modelAcao->previsao_conclusao = $modelAcao->previsao_conclusao ?
            Universal::convertDate($modelAcao->previsao_conclusao) :
            '';

        $modelAcao->orcamento_previsto = $modelAcao->orcamento_previsto ?
            Universal::convertCurrency($modelAcao->orcamento_previsto) :
            '';

        $unidadeApoioNomes = ArrayHelper::getColumn($modelAcao->acaoUnidadeApoios, 'unidadeAdministrativa.nome');

        $acaoServidorResponsavel = $modelAcao->acaoServidorResponsavel;

        if (!empty($acaoServidorResponsavel)) {
            $servidorResponsavel = $acaoServidorResponsavel->servidor;

            $servidorResponsavelNome = sprintf(
                "%s (%s)",
                $servidorResponsavel->nome,
                $servidorResponsavel->unidadeAdministrativa->nome
            );
        }

        $acaoServidorsEnvolvido = $modelAcao->acaoServidorsEnvolvido;

        if (!empty($acaoServidorsEnvolvido)) {
            foreach ($acaoServidorsEnvolvido as $acaoServidorEnvolvido) {
                $servidorEnvolvido = $acaoServidorEnvolvido->servidor;

                $servidorEnvolvidoNomes[] = sprintf(
                    "%s (%s)",
                    $servidorEnvolvido->nome,
                    $servidorEnvolvido->unidadeAdministrativa->nome
                );
            }
        }

        $modelAcaoExecucao = $modelAcao->acaoExecucao;

        if (!empty($modelAcaoExecucao)) {
            $modelAcaoExecucao->data_inicio = $modelAcaoExecucao->data_inicio ?
                Universal::convertDate($modelAcaoExecucao->data_inicio) :
                '';

            $modelAcaoExecucao->data_conclusao = $modelAcaoExecucao->data_conclusao ?
                Universal::convertDate($modelAcaoExecucao->data_conclusao) :
                '';

            $modelAcaoExecucao->orcamento_executado = $modelAcaoExecucao->orcamento_executado ?
                Universal::convertCurrency($modelAcaoExecucao->orcamento_executado) :
                '';

            $fatorLimitanteNomes = ArrayHelper::getColumn(
                $modelAcaoExecucao->acaoExecucaoFatorLimitantes,
                'fatorLimitante.nome'
            );
        } else {
            $modelAcaoExecucao = new AcaoExecucao();
        }

        $modelAcaoMonitoramento = $modelAcao->acaoMonitoramento;

        $modelAcaoMonitoramento = empty($modelAcaoMonitoramento) ? new AcaoMonitoramento() : $modelAcaoMonitoramento;

        $modelsAcaoMonitoramentoRecomendacao = $modelAcaoMonitoramento->acaoMonitoramentoRecomendacaos;

        $modelAcaoAvaliacaoRecomendacaos = $modelAcao->acaoAvaliacaoRecomendacaos;

        $modelAcaoAvaliacaoRecomendacaos = empty($modelAcaoAvaliacaoRecomendacaos) ?
            [new AcaoAvaliacaoRecomendacao()] :
            $modelAcaoAvaliacaoRecomendacaos;

        return $this->render('view', [
            'modelAcao' => $modelAcao,
            'modelAcaoExecucao' => $modelAcaoExecucao,
            'modelAcaoMonitoramento' => $modelAcaoMonitoramento,
            'modelsAcaoMonitoramentoRecomendacao' => $modelsAcaoMonitoramentoRecomendacao,
            'modelAcaoAvaliacaoRecomendacaos' => $modelAcaoAvaliacaoRecomendacaos,
            'userOrgaoNome' => $userOrgaoNome,
            'tipoNomes' => $tipoNomes,
            'unidadeApoioNomes' => $unidadeApoioNomes,
            'servidorResponsavelNome' => $servidorResponsavelNome ?? '',
            'servidorEnvolvidoNomes' => $servidorEnvolvidoNomes ?? [],
            'fatorLimitanteNomes' => $fatorLimitanteNomes ?? [],
        ]);
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
