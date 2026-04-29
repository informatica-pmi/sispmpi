<?php

namespace app\modules\monitorar\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitorar\models\Reuniao;

/**
 * ReuniaoSearch represents the model behind the search form of `app\modules\monitorar\models\Reuniao`.
 */
class ReuniaoSearch extends Reuniao
{
    public $globalSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plano_integridade_id', 'usuario_id'], 'integer'],
            [['data', 'nome', 'pauta', 'registro', 'created_at', 'globalSearch'], 'safe'],
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
        $query = Reuniao::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'data' => SORT_DESC,
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
            'plano_integridade_id' => $this->plano_integridade_id,
            'data' => $this->data,
            'usuario_id' => $this->usuario_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'nome', $this->globalSearch],
            ['like', 'pauta', $this->globalSearch],
            ['like', 'registro', $this->globalSearch]
        ]);

        return $dataProvider;
    }
}
