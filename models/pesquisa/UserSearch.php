<?php

namespace app\models\pesquisa;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'cadastrado_por', 'unidade_administrativa_id'], 'integer'],
            [['nome', 'matricula', 'login', 'senha', 'cargo', 'email', 'telefone', 'orgao_id', 'auth_key', 'password_reset_token', 'created_at', 'perfil'], 'safe'],
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
        $conditionQuery = User::getPerfil() === User::PERFIL_AUDITOR;

        $query = User::find();

        // add conditions that should always apply here
        $query->joinWith(['authAssignments', 'orgao']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
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
            'status' => $this->status,
            'unidade_administrativa_id' => $this->unidade_administrativa_id,
            'cadastrado_por' => $this->cadastrado_por,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'usuario.nome', $this->nome])
            ->andFilterWhere(['like', 'masp', $this->masp])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'senha', $this->senha])
            ->andFilterWhere(['like', 'cargo', $this->cargo])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'orgao.nome', $conditionQuery ? User::getIdentidade('orgao', 'nome') : $this->orgao_id]);

        if (empty($this->perfil)) {
            $query->andFilterWhere(['auth_assignment.item_name' => $conditionQuery ? User::getFilterAuditor() : User::getFilterCge()]);
        } else {
            $query->andFilterWhere(['auth_assignment.item_name' => $this->perfil]);
        }

        return $dataProvider;
    }
}
