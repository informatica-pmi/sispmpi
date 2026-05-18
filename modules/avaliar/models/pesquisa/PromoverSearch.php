<?php

namespace app\modules\avaliar\models\pesquisa;

use app\modules\avaliar\models\Promover;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PromoverSearch represents the model behind the search form of `app\modules\avaliar\models\Promover`.
 */
class PromoverSearch extends Promover
{
    public $dataFim;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'horas_trabalho', 'plano_integridade_id', 'orgao_id'], 'integer'],
            [['data', 'acao_desenvolvida', 'created_at', 'dataFim'], 'safe'],
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
        $query = Promover::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'horas_trabalho' => $this->horas_trabalho,
            'plano_integridade_id' => $this->plano_integridade_id,
            'created_at' => $this->created_at,
            'orgao_id' => $this->orgao_id,
        ]);

        $query->andFilterWhere(['like', 'acao_desenvolvida', $this->acao_desenvolvida]);

        if (!empty($this->data) && !empty($this->dataFim)) {
            $query->andFilterWhere(['between', 'data', "{$this->data} 00:00:00", "{$this->dataFim} 23:59:59"]);
        }
        return $dataProvider;
    }
}
