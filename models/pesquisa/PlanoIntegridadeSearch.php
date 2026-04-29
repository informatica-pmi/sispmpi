<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PlanoIntegridade;

/**
 * PlanoIntegridadeSearch represents the model behind the search form of `app\models\PlanoIntegridade`.
 */
class PlanoIntegridadeSearch extends PlanoIntegridade
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'orgao_id', 'plano_integridade_referencia_id'], 'integer'],
            [['edicao', 'created_at'], 'safe'],
            [['versao'], 'number'],
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
        $query = PlanoIntegridade::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'versao' => $this->versao,
            'status' => $this->status,
            'orgao_id' => $this->orgao_id,
            'plano_integridade_referencia_id' => $this->plano_integridade_referencia_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'edicao', $this->edicao]);

        return $dataProvider;
    }
}
