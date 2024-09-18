<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Historico;

/**
 * HistoricoSearch represents the model behind the search form of `app\models\Historico`.
 */
class HistoricoSearch extends Historico
{
    public $data_inicio;
    public $data_fim;
    public $acaoIds;
    public $tipo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_registro', 'multiple', 'usuario_id', 'tipo'], 'integer'],
            [['action', 'model', 'campo', 'antigo_valor', 'novo_valor', 'justificativa', 'usuario_perfil', 'created_at', 'data_inicio', 'data_fim', 'acaoIds'], 'safe'],
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
        $query = Historico::find();

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
            'id_registro' => $this->id_registro,
            'multiple' => $this->multiple,
            'usuario_id' => $this->usuario_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'campo', $this->campo])
            ->andFilterWhere(['like', 'antigo_valor', $this->antigo_valor])
            ->andFilterWhere(['like', 'novo_valor', $this->novo_valor])
            ->andFilterWhere(['like', 'justificativa', $this->justificativa])
            ->andFilterWhere(['like', 'usuario_perfil', $this->usuario_perfil]);

        return $dataProvider;
    }
}
