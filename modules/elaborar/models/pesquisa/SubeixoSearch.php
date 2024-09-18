<?php

namespace app\modules\elaborar\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\elaborar\models\Subeixo;

/**
 * SubeixoSearch represents the model behind the search form of `app\models\Subeixo`.
 */
class SubeixoSearch extends Subeixo
{
    public $plano_integridade_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plano_integridade_id'], 'integer'],
            [['titulo', 'descricao', 'eixo_id'], 'safe'],
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
        $query = Subeixo::find();

        // add conditions that should always apply here
        $query->joinWith(['eixo']);

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
            'eixo.plano_integridade_id' => $this->plano_integridade_id,
        ]);

        $query->andFilterWhere(['like', 'subeixo.titulo', $this->titulo])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'eixo.titulo', $this->eixo_id]);

        return $dataProvider;
    }
}
