<?php

namespace app\modules\avaliar\models;

use Yii;
use app\models\PlanoIntegridade;
use app\modules\admin\models\Orgao;

/**
 * This is the model class for table "promover_integridade".
 *
 * @property int $id
 * @property string $data
 * @property string $acao_desenvolvida
 * @property int $horas_trabalho
 * @property int $plano_integridade_id
 * @property int $orgao_id
 * @property string $created_at
 *
 * @property Orgao $orgao
 * @property PlanoIntegridade $planoIntegridade
 */
class Promover extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promover_integridade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'acao_desenvolvida', 'horas_trabalho', 'plano_integridade_id', 'orgao_id'], 'required'],
            [['data', 'created_at'], 'safe'],
            [['horas_trabalho', 'plano_integridade_id'], 'integer'],
            [['acao_desenvolvida'], 'string'],
            [['orgao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['orgao_id' => 'id']],
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
            'data' => 'Data',
            'acao_desenvolvida' => 'Ação desenvolvida',
            'horas_trabalho' => 'Horas de trabalho',
            'plano_integridade_id' => 'Plano Integridade ID',
            'orgao_id' => 'Orgao ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Orgao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgao()
    {
        return $this->hasOne(Orgao::className(), ['id' => 'orgao_id']);
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
}
