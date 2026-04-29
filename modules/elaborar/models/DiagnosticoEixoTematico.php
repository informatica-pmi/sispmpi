<?php

namespace app\modules\elaborar\models;

use app\models\EixoTematico;
use Yii;

/**
 * This is the model class for table "diagnostico_eixo_tematico".
 *
 * @property int $id
 * @property int $eixo_tematico_id
 * @property int $diagnostico_id
 *
 * @property Diagnostico $diagnostico
 * @property EixoTematico $eixoTematico
 */
class DiagnosticoEixoTematico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_eixo_tematico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eixo_tematico_id', 'diagnostico_id'], 'required'],
            [['eixo_tematico_id', 'diagnostico_id'], 'integer'],
            [['diagnostico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Diagnostico::className(), 'targetAttribute' => ['diagnostico_id' => 'id']],
            [['eixo_tematico_id'], 'exist', 'skipOnError' => true, 'targetClass' => EixoTematico::className(), 'targetAttribute' => ['eixo_tematico_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eixo_tematico_id' => 'Eixo Tematico ID',
            'diagnostico_id' => 'Diagnostico ID',
        ];
    }

    /**
     * Gets query for [[Diagnostico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnostico()
    {
        return $this->hasOne(Diagnostico::className(), ['id' => 'diagnostico_id']);
    }

    /**
     * Gets query for [[EixoTematico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEixoTematico()
    {
        return $this->hasOne(EixoTematico::className(), ['id' => 'eixo_tematico_id']);
    }
}
