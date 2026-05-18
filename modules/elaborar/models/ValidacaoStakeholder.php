<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\Stakeholder;

/**
 * This is the model class for table "validacao_stakeholder".
 *
 * @property int $id
 * @property int $validacao_id
 * @property int $stakeholder_id
 *
 * @property Stakeholder $stakeholder
 * @property Validacao $validacao
 */
class ValidacaoStakeholder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'validacao_stakeholder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['validacao_id', 'stakeholder_id'], 'required'],
            [['validacao_id', 'stakeholder_id'], 'integer'],
            [['stakeholder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stakeholder::className(), 'targetAttribute' => ['stakeholder_id' => 'id']],
            [['validacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Validacao::className(), 'targetAttribute' => ['validacao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'validacao_id' => 'Validacao ID',
            'stakeholder_id' => 'Stakeholder ID',
        ];
    }

    /**
     * Gets query for [[Stakeholder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStakeholder()
    {
        return $this->hasOne(Stakeholder::className(), ['id' => 'stakeholder_id']);
    }

    /**
     * Gets query for [[Validacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValidacao()
    {
        return $this->hasOne(Validacao::className(), ['id' => 'validacao_id']);
    }
}
