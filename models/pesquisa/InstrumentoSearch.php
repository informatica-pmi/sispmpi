<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Instrumento;

/**
 * InstrumentoSearch represents the model behind the search form of `app\models\Instrumento`.
 */
class InstrumentoSearch extends Instrumento
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nome', 'created_at', 'orgao_id'], 'safe'],
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
        $query = Instrumento::find();

        // add conditions that should always apply here
        $query->joinWith(['orgao']);

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'instrumento.nome', $this->nome])
            ->andFilterWhere(['like', 'orgao.sigla', $this->orgao_id]);

        return $dataProvider;
    }
}
