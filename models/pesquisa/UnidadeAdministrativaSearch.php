<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\UnidadeAdministrativa;
use app\models\User;

/**
 * UnidadeAdministrativaSearch represents the model behind the search form of `app\models\UnidadeAdministrativa`.
 */
class UnidadeAdministrativaSearch extends UnidadeAdministrativa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nome', 'orgao_id', 'created_at'], 'safe'],
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
        $query = UnidadeAdministrativa::find();

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

        $query->andFilterWhere(['like', 'unidade_administrativa.nome', $this->nome])
            ->andFilterWhere([
                'like',
                'orgao.nome',
                ArrayHelper::isIn(
                    User::getPerfil(),
                    [User::PERFIL_TI, User::PERFIL_ADMINISTRADOR]
                ) ? $this->orgao_id : User::getIdentidade('orgao', 'nome')
            ]);

        return $dataProvider;
    }
}
