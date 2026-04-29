<?php

namespace app\modules\monitorar\controllers;

use Yii;
use app\models\Acao;
use app\models\User;
use app\models\Status;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\PlanoIntegridade;
use app\models\pesquisa\AcaoSearch;
use app\modules\admin\models\Orgao;
use app\components\helpers\Universal;
use app\models\AcaoAvaliacaoRecomendacao;
use app\modules\monitorar\models\Reuniao;
use app\models\AcaoMonitoramentoRecomendacao;
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
     * Página inicial do módulo monitorar
     * @return mixed
     */
    public function actionIndex()
    {
        $modelOrgao = Orgao::findOne(User::getIdentidade('orgao_id'));

        $userOrgaoNome = $modelOrgao->nome;

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $modelOrgao->id,
            'status' => [Status::PLANO_PUBLICADO, Status::PLANO_ELABORACAO]
        ]);

        if (!Universal::temPermissao('modulo-monitoramento')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!$modelPlano || ($modelPlano->status == Status::PLANO_ELABORACAO && !$isObservador)) {
            Universal::flash('error', 'Não foi encontrado nenhum programa de integridade.');
            return $this->redirect(['/site/index']);
        }

        $searchModelAcao = new AcaoSearch();

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

        $totalMonitoramentoAcaoRecomendacaos = count($acaoMonitoramentoRecomendacaos);

        $countMonitoramentoNaoRespondidas = AcaoMonitoramentoRecomendacao::find()
            ->where(['resposta' => null])
            ->andWhere(['in', 'id', $acaoMonitoramentoRecomendacaoIds])
            ->count();

        $porcentagemMonitoramentoNaoRespondidas = Universal::calcPorcentagem(
            $countMonitoramentoNaoRespondidas,
            $totalMonitoramentoAcaoRecomendacaos
        );

        $countMonitoramentoRespondidas = AcaoMonitoramentoRecomendacao::find()
            ->where(['not', ['resposta' => null]])
            ->andWhere(['in', 'id', $acaoMonitoramentoRecomendacaoIds])
            ->count();

        $porcentagemMonitoramentoRespondidas = Universal::calcPorcentagem(
            $countMonitoramentoRespondidas,
            $totalMonitoramentoAcaoRecomendacaos
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

        $lastAcaoMonitoramento = AcaoMonitoramento::find()
            ->where(['in', 'acao_id', $acaosIds])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $lastRecomendacao = AcaoMonitoramentoRecomendacao::find()
            ->joinWith(['acaoMonitoramento'])
            ->where(['in', 'acao_monitoramento.acao_id', $acaosIds])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $lastReuniao = Reuniao::find()
            ->where(['plano_integridade_id' => $modelPlano->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $dateLastChange = max([
            Universal::valueField($lastAcaoMonitoramento, 'created_at'),
            Universal::valueField($lastRecomendacao, 'created_at'),
            Universal::valueField($lastReuniao, 'created_at')
        ]);

        if ($modelPlano->status != Status::PLANO_PUBLICADO) {
            $dateConclusion = 'Não se aplica/Não definido';
        } else {
            $dateConclusion = Universal::convertDate($modelPlano->publicacao->created_at);
        }

        $lastDateModified = !is_null($dateLastChange) ? Universal::convertDate($dateLastChange) : $dateConclusion;

        $existsPlanoIntegridadeNovo = $modelPlano->getPlanoIntegridadeNovos()
            ->where(['autorizado' => null])
            ->count();

        $countPlanoRecomendacaoWithoutAnswer = $modelPlano->getPlanoIntegridadeRecomendacaos()
            ->where(['resposta' => ''])
            ->orWhere(['resposta' => null])
            ->count();

        return $this->render('index', [
            'modelPlano' => $modelPlano,
            'userOrgaoNome' => $userOrgaoNome,
            'dateConclusion' => $dateConclusion,
            'lastDateModified' => $lastDateModified,
            'chartStatus' => $chartStatus,
            'chartRecomendacaos' => $chartRecomendacaos,
            'pontosAtencao' => $pontosAtencao,
            'isObservador' => $isObservador,
            'searchModelAcao' => $searchModelAcao,
            'existsPlanoIntegridadeNovo' => $existsPlanoIntegridadeNovo,
            'countPlanoRecomendacaoWithoutAnswer' => $countPlanoRecomendacaoWithoutAnswer
        ]);
    }
}
