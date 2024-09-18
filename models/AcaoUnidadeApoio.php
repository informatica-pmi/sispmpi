<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acao_unidade_apoio".
 *
 * @property int $id
 * @property int $acao_id
 * @property int $unidade_administrativa_id
 *
 * @property Acao $acao
 * @property UnidadeAdministrativa $unidadeAdministrativa
 */
class AcaoUnidadeApoio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_unidade_apoio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acao_id', 'unidade_administrativa_id'], 'required'],
            [['acao_id', 'unidade_administrativa_id'], 'integer'],
            [['acao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_id' => 'id']],
            [['unidade_administrativa_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadeAdministrativa::className(), 'targetAttribute' => ['unidade_administrativa_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acao_id' => 'Acao ID',
            'unidade_administrativa_id' => 'Unidade Administrativa ID',
        ];
    }

    /**
     * Gets query for [[Acao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcao()
    {
        return $this->hasOne(Acao::className(), ['id' => 'acao_id']);
    }

    /**
     * Gets query for [[UnidadeAdministrativa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadeAdministrativa()
    {
        return $this->hasOne(UnidadeAdministrativa::className(), ['id' => 'unidade_administrativa_id']);
    }
}
