<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\PlanoIntegridade;

/**
 * This is the model class for table "validacao".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property string $data_inicio
 * @property string $info_complementar
 * @property string $data_conclusao
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property ValidacaoStakeholder[] $validacaoStakeholders
 */
class Validacao extends \yii\db\ActiveRecord
{
    public $stakeholderIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'validacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'data_inicio', 'info_complementar', 'data_conclusao', 'stakeholderIds'], 'required'],
            [['plano_integridade_id'], 'integer'],
            [['data_inicio', 'data_conclusao', 'created_at'], 'safe'],
            [['info_complementar'], 'string'],
            [['plano_integridade_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plano_integridade_id' => 'Plano Integridade ID',
            'data_inicio' => 'Data de início ',
            'info_complementar' => 'Informações complementares e constatações gerais sobre o processo de validação do programa e do plano de integridade',
            'data_conclusao' => 'Data de conclusão',
            'created_at' => 'Created At',
            'stakeholderIds' => 'Stakeholders',
        ];
    }

    /**
     * Gets query for [[PlanoIntegridade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridade()
    {
        return $this->hasOne(PlanoIntegridade::className(), ['id' => 'plano_integridade_id']);
    }

    /**
     * Gets query for [[ValidacaoStakeholders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValidacaoStakeholders()
    {
        return $this->hasMany(ValidacaoStakeholder::className(), ['validacao_id' => 'id']);
    }
}
