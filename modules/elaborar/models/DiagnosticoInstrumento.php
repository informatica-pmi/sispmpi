<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\Instrumento;

/**
 * This is the model class for table "diagnostico_instrumento".
 *
 * @property int $id
 * @property int $instrumento_id
 * @property int $diagnostico_id
 *
 * @property Diagnostico $diagnostico
 * @property Instrumento $instrumento
 */
class DiagnosticoInstrumento extends \yii\db\ActiveRecord
{
    public $instrumentoIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_instrumento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instrumentoIds'], 'required'],
            [['instrumento_id', 'diagnostico_id'], 'integer'],
            [['diagnostico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Diagnostico::className(), 'targetAttribute' => ['diagnostico_id' => 'id']],
            [['instrumento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instrumento::className(), 'targetAttribute' => ['instrumento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instrumento_id' => 'Instrumento ID',
            'diagnostico_id' => 'Diagnostico ID',
            'instrumentoIds' => 'Quais instrumentos foram utilizados para o diagnóstico do ambiente de integridade?',
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
     * Gets query for [[Instrumento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstrumento()
    {
        return $this->hasOne(Instrumento::className(), ['id' => 'instrumento_id']);
    }
}
