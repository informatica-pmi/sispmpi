<?php

namespace app\modules\avaliar\controllers;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\AcaoAvaliacaoRecomendacao;
use app\models\AcaoMonitoramentoRecomendacao;
use app\models\PlanoIntegridade;
use app\models\PlanoIntegridadeRecomendacao;
use app\models\User;
use app\models\Status;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * Default controller for the `avaliar` module
 */
class DefaultController extends Controller
{
    /**
     * Renderizando a pagina inicial do módulo
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userOrgaoNome = User::getIdentidade('orgao', 'nome');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO]
        ]);

        if (!Universal::temPermissao('modulo-avaliacao')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if (!$modelPlano) {
            Universal::flash('error', 'Não foi encontrado nenhum plano de integridade.');
            return $this->redirect(['/site/index']);
        }

        $planoIntegridadeRecomendationIsCreate = empty($modelPlano->planoIntegridadeRecomendacaos);

        $eixosIds = ArrayHelper::getColumn($modelPlano->eixos, 'id');

        $acaos = Acao::findAll(['eixo_id' => $eixosIds]);

        $acaosIds = ArrayHelper::getColumn($acaos, 'id');

        $chartStatus = [
            'naoInicializada' => Acao::find()
                ->where(['status' => Status::ACAO_N_INICIALIZADA])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
            'emAndamento' => Acao::find()
                ->where(['status' => Status::ACAO_EM_ANDAMENTO])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
            'concluida' => Acao::find()
                ->where(['status' => Status::ACAO_CONCLUIDA])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
            'descontinuada' => Acao::find()
                ->where(['status' => Status::ACAO_DESCONTINUADA])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
        ];

        $acaoMonitoramentoRecomendacaos = AcaoMonitoramentoRecomendacao::find()
            ->joinWith(['acaoMonitoramento.acao'])
            ->where(['in', 'eixo_id', $eixosIds])
            ->all();

        $acaoMonitoramentoRecomendacaoIds = ArrayHelper::getColumn($acaoMonitoramentoRecomendacaos, 'id');

        $totalMonitoramentoRecomendacaos = count($acaoMonitoramentoRecomendacaos);

        $countMonitoramentoNaoRespondidas = AcaoMonitoramentoRecomendacao::find()
            ->where(['resposta' => null])
            ->andWhere(['in', 'id', $acaoMonitoramentoRecomendacaoIds])
            ->count();

        $porcentagemMonitoramentoNaoRespondidas = Universal::calcPorcentagem(
            $countMonitoramentoNaoRespondidas,
            $totalMonitoramentoRecomendacaos
        );

        $countMonitoramentoRespondidas = AcaoMonitoramentoRecomendacao::find()
            ->where(['not', ['resposta' => null]])
            ->andWhere(['in', 'id', $acaoMonitoramentoRecomendacaoIds])
            ->count();

        $porcentagemMonitoramentoRespondidas = Universal::calcPorcentagem(
            $countMonitoramentoRespondidas,
            $totalMonitoramentoRecomendacaos
        );

        $acaoAvaliacaoRecomendacaos = AcaoAvaliacaoRecomendacao::find()
            ->joinWith(['acao'])
            ->where(['in', 'eixo_id', $eixosIds])
            ->all();

        $acaoAvaliacaoRecomendacaoIds = ArrayHelper::getColumn($acaoAvaliacaoRecomendacaos, 'id');

        $totalAvaliacaoRecomendacaos = count($acaoAvaliacaoRecomendacaos);

        $countAvaliacaoNaoRespondidas = AcaoAvaliacaoRecomendacao::find()
            ->where(['resposta' => null])
            ->andWhere(['in', 'id', $acaoAvaliacaoRecomendacaoIds])
            ->count();

        $countAvaliacaoRespondidas = AcaoAvaliacaoRecomendacao::find()
            ->where(['not', ['resposta' => null]])
            ->andWhere(['in', 'id', $acaoAvaliacaoRecomendacaoIds])
            ->count();

        $porcentagemAvaliacaoNaoRespondidas = Universal::calcPorcentagem(
            $countAvaliacaoNaoRespondidas,
            $totalAvaliacaoRecomendacaos
        );

        $porcentagemAvaliacaoRespondidas = Universal::calcPorcentagem(
            $countAvaliacaoRespondidas,
            $totalAvaliacaoRecomendacaos
        );

        $chartRecomendacaos = [
            'monitoramento' => [
                'naoRespondidasTotal' => $countMonitoramentoNaoRespondidas,
                'naoRespondidasPorcentagem' => $porcentagemMonitoramentoNaoRespondidas,
                'respondidasTotal' => $countMonitoramentoRespondidas,
                'respondidasPorcentagem' => $porcentagemMonitoramentoRespondidas,
            ],
            'controleInterno' => [
                'naoRespondidasTotal' => $countAvaliacaoNaoRespondidas,
                'naoRespondidasPorcentagem' => $porcentagemAvaliacaoNaoRespondidas,
                'respondidasTotal' => $countAvaliacaoRespondidas,
                'respondidasPorcentagem' => $porcentagemAvaliacaoRespondidas,
            ],
        ];

        $pontosAtencao = [
            'atrasadasInicio' => Acao::find()
                ->where(['<', 'previsao_inicio', date('Y-m-d')])
                ->andWhere(['status' => Status::ACAO_N_INICIALIZADA])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
            'atrasadasConclusao' => Acao::find()
                ->where(['<', 'previsao_conclusao', date('Y-m-d')])
                ->andWhere(['not in', 'status', [Status::ACAO_CONCLUIDA, Status::ACAO_DESCONTINUADA]])
                ->andWhere(['in', 'eixo_id', $eixosIds])
                ->count(),
            'naoImplementacao' => Acao::find()
                ->joinWith(['acaoMonitoramento monitoramento'])
                ->where([
                    'in',
                    'monitoramento.risco_n_implementacao',
                    [AcaoMonitoramento::RISCO_ALTO, AcaoMonitoramento::RISCO_EXTREMO]
                ])
                ->andWhere(['in', 'acao.eixo_id', $eixosIds])
                ->count(),
        ];

        $modelPlanoIntegridadeRecomendacao = PlanoIntegridadeRecomendacao::find()
            ->where(['plano_integridade_id' => $modelPlano->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $modelAcaoAvaliacaoRecomendacao = AcaoAvaliacaoRecomendacao::find()
            ->where(['in', 'acao_id', $acaosIds])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $dateLastChange = max([
            Universal::valueField($modelPlanoIntegridadeRecomendacao, 'created_at'),
            Universal::valueField($modelAcaoAvaliacaoRecomendacao, 'created_at')
        ]);

        if ($modelPlano->status != Status::PLANO_PUBLICADO) {
            $dateConclusion = 'Não se aplica/Não definido';
            $disabledAnchorPlanoElaboracao = 'disabled';
        } else {
            $dateConclusion = Universal::convertDate($modelPlano->publicacao->created_at);
            $disabledAnchorPlanoElaboracao = '';
        }

        $lastDateModified = !is_null($dateLastChange) ? Universal::convertDate($dateLastChange) : $dateConclusion;

        $existsPlanoIntegridadeNovo = $modelPlano->getPlanoIntegridadeNovos()
            ->where(['autorizado' => null])
            ->count();

        return $this->render('index', [
            'modelPlano' => $modelPlano,
            'userOrgaoNome' => $userOrgaoNome,
            'chartStatus' => $chartStatus,
            'chartRecomendacaos' => $chartRecomendacaos,
            'pontosAtencao' => $pontosAtencao,
            'planoIntegridadeRecomendationIsCreate' => $planoIntegridadeRecomendationIsCreate,
            'dateConclusion' => $dateConclusion,
            'lastDateModified' => $lastDateModified,
            'existsPlanoIntegridadeNovo' => $existsPlanoIntegridadeNovo,
            'disabledAnchorPlanoElaboracao' => $disabledAnchorPlanoElaboracao,
        ]);
    }
}
