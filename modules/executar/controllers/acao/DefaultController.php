<?php

namespace app\modules\executar\controllers\acao;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\helpers\OrganSpecific;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\FatorLimitante;
use app\models\Servidor;
use app\models\Tipo;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\models\AcaoMonitoramentoRecomendacao;
use app\modules\executar\models\AcaoExecucao;
use app\modules\monitorar\models\AcaoMonitoramento;

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
     * Visualiza uma ação existente
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionView($acaoId)
    {
        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelAcao = $this->findModel($acaoId);

        $modelAcao->scenario = Acao::SCENARIO_EXECUTAR;

        $plano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $plano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelServidor = empty($modelAcao->acaoServidorResponsavel) ?
            new Servidor() :
            $modelAcao->acaoServidorResponsavel->servidor;

        $modelServidor->scenario = Servidor::SCENARIO_EXECUTAR;

        $servidorsEnvolvido = ArrayHelper::getColumn($modelAcao->acaoServidorsEnvolvido, 'servidor');

        $modelsServidor = empty($servidorsEnvolvido) ? [new Servidor()] : $servidorsEnvolvido;

        foreach ($modelsServidor as $servidorEnvolvido) {
            $servidorEnvolvido->scenario = Servidor::SCENARIO_EXECUTAR;
        }

        $modelAcao->tipoIds = ArrayHelper::getColumn($modelAcao->acaoTipos, 'tipo_id');

        $modelAcaoExecucao = empty($modelAcao->acaoExecucao) ? new AcaoExecucao() : $modelAcao->acaoExecucao;

        $modelAcaoExecucao->fatoresLimitantesIds = ArrayHelper::getColumn(
            $modelAcaoExecucao->acaoExecucaoFatorLimitantes,
            'fator_limitante_id'
        );

        $acaoExecucaoEvidenciaFiles = $modelAcaoExecucao->acaoExecucaoArquivos;

        $disabledFieldAcaoExecucaoEvidenciaFiles = count($acaoExecucaoEvidenciaFiles) >= 2 ? 'disabled' : '';

        $optionsTipo = OrganSpecific::search(Tipo::className());

        $optionsUnidadeAdministrativa = ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->orderBy(['nome' => 'SORT_ASC'])
                ->all(),
            'id',
            'nome'
        );

        $modelAcao->unidadeApoioIds = ArrayHelper::getColumn(
            $modelAcao->acaoUnidadeApoios,
            'unidade_administrativa_id'
        );

        $optionsFatorLimitante = OrganSpecific::search(FatorLimitante::className());

        $acaoMonitoramento = $modelAcao->acaoMonitoramento;

        if (empty($acaoMonitoramento)) {
            $acaoMonitoramento = new AcaoMonitoramento();
            $acaoMonitoramento->risco_n_implementacao = '';
        } else {
            $acaoMonitoramento->risco_n_implementacao = AcaoMonitoramento::getRisco(
                $acaoMonitoramento->risco_n_implementacao
            );
        }

        $acaoMonitoramentoRecomendacaos = $acaoMonitoramento->acaoMonitoramentoRecomendacaos;

        $modelsAcaoMonitoramentoRecomendacao = empty($acaoMonitoramentoRecomendacaos) ?
            [new AcaoMonitoramentoRecomendacao()] :
            $acaoMonitoramentoRecomendacaos;

        $acaoAvaliacaoRecomendacaos = $modelAcao->acaoAvaliacaoRecomendacaos;

        $modelsAcaoAvaliacaoRecomendacao = empty($acaoAvaliacaoRecomendacaos) ?
            [new AcaoAvaliacaoRecomendacao()] :
            $acaoAvaliacaoRecomendacaos;

        $disabledAccordionResponsabilidade = !$modelAcao->classificacao &&
            !$modelAcao->tipoIds &&
            !$modelAcao->previsao_inicio;

        $disabledAccordionExecucao = $disabledAccordionResponsabilidade || $modelServidor->isNewRecord;

        $disabledAccordionMonitoramento = $acaoMonitoramento->isNewRecord;

        $disableAccordionAvaliacao = $modelsAcaoAvaliacaoRecomendacao[0]->isNewRecord;

        $prepareAccordions = [
            'responsabilidade' => $disabledAccordionResponsabilidade,
            'execucao' => $disabledAccordionExecucao,
            'monitoramento' => $disabledAccordionMonitoramento,
            'avaliacao' => $disableAccordionAvaliacao,
        ];

        return $this->render('view', [
            'modelAcao' => $modelAcao,
            'modelServidor' => $modelServidor,
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
            'modelAcaoExecucao' => $modelAcaoExecucao,
            'modelsAcaoMonitoramentoRecomendacao' => $modelsAcaoMonitoramentoRecomendacao,
            'modelsAcaoAvaliacaoRecomendacao' => $modelsAcaoAvaliacaoRecomendacao,
            'acaoMonitoramento' => $acaoMonitoramento,
            'acaoExecucaoEvidenciaFiles' => $acaoExecucaoEvidenciaFiles,
            'userOrgaoNome' => $userOrgaoNome,
            'prepareAccordions' => $prepareAccordions,
            'optionsTipo' => $optionsTipo,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
            'optionsFatorLimitante' => $optionsFatorLimitante,
            'disabledFieldAcaoExecucaoEvidenciaFiles' => $disabledFieldAcaoExecucaoEvidenciaFiles,
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
