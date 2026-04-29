<?php

namespace app\modules\admin\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\InformacaoEstado;

/**
 * InformacaoEstadoSearch represents the model behind the search form of `app\modules\admin\models\InformacaoEstado`.
 */
class InformacaoEstadoSearch extends InformacaoEstado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quantitativo_pessoal'], 'integer'],
            [['ano'], 'safe'],
            [['orcamento'], 'number'],
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
        $query = InformacaoEstado::find();

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
            'orcamento' => $this->orcamento,
            'quantitativo_pessoal' => $this->quantitativo_pessoal,
        ]);

        // like
        $query->andFilterWhere(['like', 'ano', $this->ano]);

        return $dataProvider;
    }
}
