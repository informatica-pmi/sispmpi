<?php

namespace app\modules\elaborar\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\elaborar\models\Eixo;

/**
 * EixoSearch represents the model behind the search form of `app\modules\elaborar\models\Eixo`.
 */
class EixoSearch extends Eixo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plano_integridade_id'], 'integer'],
            [['titulo', 'descricao'], 'safe'],
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
        $query = Eixo::find();

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
            'plano_integridade_id' => $this->plano_integridade_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}
