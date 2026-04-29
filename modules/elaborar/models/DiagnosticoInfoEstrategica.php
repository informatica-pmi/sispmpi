<?php

namespace app\modules\elaborar\models;

use Yii;

/**
 * This is the model class for table "diagnostico_info_estrategica".
 *
 * @property int $id
 * @property int $diagnostico_id
 * @property string $missao
 * @property string $visao
 * @property string $valores
 * @property string $estrutura_organica
 * @property string $competencias
 * @property string $atribuicoes
 * @property string $created_at
 *
 * @property Diagnostico $diagnostico
 */
class DiagnosticoInfoEstrategica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_info_estrategica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diagnostico_id', 'missao', 'visao', 'estrutura_organica', 'competencias', 'atribuicoes'], 'required'],
            [['diagnostico_id'], 'integer'],
            [['missao', 'visao', 'valores', 'estrutura_organica', 'competencias', 'atribuicoes'], 'string'],
            [['created_at'], 'safe'],
            [['valores'], 'default', 'value' => null],
            [['diagnostico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Diagnostico::className(), 'targetAttribute' => ['diagnostico_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'diagnostico_id' => 'Diagnostico ID',
            'missao' => 'Missão',
            'visao' => 'Visão',
            'valores' => 'Valores',
            'estrutura_organica' => 'Estrutura Orgânica',
            'competencias' => 'Competências',
            'atribuicoes' => 'Atribuições',
            'created_at' => 'Created At',
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
}
