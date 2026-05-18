<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\PlanoIntegridade;

/**
 * This is the model class for table "diagnostico".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property int $page_key
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property DiagnosticoCiencia $diagnosticoCiencia
 * @property DiagnosticoEixoTematico[] $diagnosticoEixoTematicos
 * @property DiagnosticoInfoEstrategica $diagnosticoInfoEstrategica
 * @property DiagnosticoInstrumento[] $diagnosticoInstrumentos
 * @property DiagnosticoResultado $diagnosticoResultado
 */
class Diagnostico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'page_key'], 'required'],
            [['plano_integridade_id', 'page_key'], 'integer'],
            [['created_at'], 'safe'],
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
            'page_key' => 'Page Key',
            'created_at' => 'Created At',
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
     * Gets query for [[DiagnosticoCiencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoCiencia()
    {
        return $this->hasOne(DiagnosticoCiencia::className(), ['diagnostico_id' => 'id']);
    }

    /**
     * Gets query for [[DiagnosticoEixoTematicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoEixoTematicos()
    {
        return $this->hasMany(DiagnosticoEixoTematico::className(), ['diagnostico_id' => 'id']);
    }

    /**
     * Gets query for [[DiagnosticoInfoEstrategica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoInfoEstrategica()
    {
        return $this->hasOne(DiagnosticoInfoEstrategica::className(), ['diagnostico_id' => 'id']);
    }

    /**
     * Gets query for [[DiagnosticoInstrumentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoInstrumentos()
    {
        return $this->hasMany(DiagnosticoInstrumento::className(), ['diagnostico_id' => 'id']);
    }

    /**
     * Gets query for [[DiagnosticoResultado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoResultado()
    {
        return $this->hasOne(DiagnosticoResultado::className(), ['diagnostico_id' => 'id']);
    }
}
