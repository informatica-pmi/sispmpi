<?php

namespace app\modules\admin\models\pesquisa;

use app\models\Status;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Orgao;
use yii\db\Query;

/**
 * OrgaoSearch represents the model behind the search form of `app\modules\admin\models\Orgao`.
 */
class VIndicadoresSearch extends Model
{
    public $orgao_nome;
    public $orgao_tipo;
    public $plano_integridade_status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orgao_nome', 'orgao_tipo', 'plano_integridade_status', 'acoes_atrasadas'], 'safe'],
        ];
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
        $query = (new Query())->from('v_indicadores');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'orgao_tipo' => $this->orgao_tipo,
            'plano_integridade_status' => $this->plano_integridade_status == Status::PLANO_N_INICIADO ? [Status::PLANO_N_INICIADO, null] : $this->plano_integridade_status
        ]);

        $query->andFilterWhere(['like', 'orgao_nome', $this->orgao_nome]);

        return $dataProvider;
    }
}
