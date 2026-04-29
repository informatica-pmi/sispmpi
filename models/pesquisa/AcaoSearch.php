<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Acao;
use app\models\Status;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * AcaoSearch represents the model behind the search form of `app\models\Acao`.
 */
class AcaoSearch extends Acao
{
    public $plano_integridade_id;
    public $filter;
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'unidade_executora', 'plano_integridade_id', 'status', 'filter'], 'integer'],
            [['titulo', 'descricao', 'objetivo', 'beneficio_instituicao', 'eixo_id', 'subeixo_id', 'globalSearch', 'id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        $arrayFilterMonitoramentoRecomendacao = [
            Acao::FILTER_MONITORAMENTO_RECOMENDACAO_N_RESPONDIDA,
            Acao::FILTER_MONITORAMENTO_RECOMENDACAO_RESPONDIDA
        ];

        $arrayFilterControleInternoRecomendacao = [
            Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_N_RESPONDIDA,
            Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_RESPONDIDA,
        ];

        $query = Acao::find();

        // add conditions that should always apply here
        $query->joinWith([
            'eixo',
            'subeixo',
            'unidadeExecutora',
            'acaoMonitoramento',
        ]);

        if (in_array($this->filter, $arrayFilterMonitoramentoRecomendacao)) {
            $query->innerJoinWith(['acaoMonitoramento.acaoMonitoramentoRecomendacaos']);
        } elseif (in_array($this->filter, $arrayFilterControleInternoRecomendacao)) {
            $query->innerJoinWith(['acaoAvaliacaoRecomendacaos']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'acao.id' => $this->id,
        ]);


        // consulta tradicional
        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'objetivo', $this->objetivo])
            ->andFilterWhere(['like', 'beneficio_instituicao', $this->beneficio_instituicao])
            ->andFilterWhere(['like', 'eixo.titulo', $this->eixo_id])
            ->andFilterWhere(['like', 'subeixo.titulo', $this->subeixo_id])
            ->andFilterWhere(['like', 'acao.titulo', $this->titulo]);

        $query->orFilterWhere(['like', 'acao.titulo', $this->globalSearch])
            ->orFilterWhere(['like', 'eixo.titulo', $this->globalSearch])
            ->orFilterWhere(['like', 'subeixo.titulo', $this->globalSearch])
            ->orFilterWhere(['like', 'unidade_administrativa.nome', $this->globalSearch])
            ->orFilterWhere(['like', 'acao.numero', $this->globalSearch]);

        $query->andWhere(['eixo.plano_integridade_id' => $this->plano_integridade_id]);

        if ($this->status) {
            $query->andWhere(['acao.status' => $this->status]);
        }

        if ($this->filter) {
            switch ($this->filter) {
                case Acao::FILTER_ATRASADA_INICIO_PREVISTO:
                    $query->andWhere(['<', 'previsao_inicio', date('Y-m-d')])
                        ->andWhere(['status' => Status::ACAO_N_INICIALIZADA]);
                    break;
                case Acao::FILTER_ATRASADA_CONCLUSAO_PREVISTO:
                    $query->andWhere(['<', 'previsao_conclusao', date('Y-m-d')])
                        ->andWhere(['not in', 'status', [Status::ACAO_CONCLUIDA, Status::ACAO_DESCONTINUADA]]);
                    break;
                case Acao::FILTER_RISCO_N_IMPLEMENTACAO_ALTO:
                    $query->andWhere([
                        'in',
                        'acao_monitoramento.risco_n_implementacao',
                        [AcaoMonitoramento::RISCO_ALTO, AcaoMonitoramento::RISCO_EXTREMO]
                    ]);
                    break;
                case Acao::FILTER_STATUS_N_INICIALIZA:
                    $query->andWhere(['acao.status' => Status::ACAO_N_INICIALIZADA]);
                    break;
                case Acao::FILTER_STATUS_EM_ANDAMENTO:
                    $query->andWhere(['acao.status' => Status::ACAO_EM_ANDAMENTO]);
                    break;
                case Acao::FILTER_STATUS_CONCLUIDA:
                    $query->andWhere(['acao.status' => Status::ACAO_CONCLUIDA]);
                    break;
                case Acao::FILTER_STATUS_DESCONTINUADA:
                    $query->andWhere(['acao.status' => Status::ACAO_DESCONTINUADA]);
                    break;
                case Acao::FILTER_MONITORAMENTO_RECOMENDACAO_N_RESPONDIDA:
                    $query->andWhere(['acao_monitoramento_recomendacao.resposta' => null]);
                    break;
                case Acao::FILTER_MONITORAMENTO_RECOMENDACAO_RESPONDIDA:
                    $query->andWhere(['not', ['acao_monitoramento_recomendacao.resposta' => null]]);
                    break;
                case Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_N_RESPONDIDA:
                    $query->andWhere(['acao_avaliacao_recomendacao.resposta' => null]);
                    break;
                case Acao::FILTER_CONTROLE_INTERNO_RECOMENDACAO_RESPONDIDA:
                    $query->andWhere(['not', ['acao_avaliacao_recomendacao.resposta' => null]]);
                    break;
            }
        }

        return $dataProvider;
    }
}
