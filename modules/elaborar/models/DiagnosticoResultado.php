<?php

namespace app\modules\elaborar\models;

use Yii;

/**
 * This is the model class for table "diagnostico_resultado".
 *
 * @property int $id
 * @property int $diagnostico_id
 * @property string $descricao
 * @property string|null $objetivos_trabalhados
 * @property string|null $objetivos_estrategicos
 * @property string|null $estrutura_governanca
 * @property string|null $periodicidade_monitoramentos
 * @property string|null $periodicidade_avaliacoes
 * @property string|null $periodicidade_atualizacoes
 * @property string|null $aspectos_comunicacao
 * @property string|null $aspectos_capacitacao
 * @property string $created_at
 *
 * @property Diagnostico $diagnostico
 */
class DiagnosticoResultado extends \yii\db\ActiveRecord
{
    public const SCENARIO_UPDATE_PROGRAMA = 'updatePrograma';

    public $eixoTematicoIds;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_resultado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diagnostico_id', 'descricao'], 'required'],
            [['objetivos_trabalhados', 'objetivos_estrategicos', 'estrutura_governanca', 'periodicidade_monitoramentos', 'periodicidade_avaliacoes', 'periodicidade_atualizacoes', 'aspectos_comunicacao', 'aspectos_capacitacao', 'eixoTematicoIds'], 'required', 'on' => self::SCENARIO_UPDATE_PROGRAMA],
            [['diagnostico_id'], 'integer'],
            [['descricao', 'objetivos_trabalhados', 'objetivos_estrategicos', 'estrutura_governanca', 'aspectos_comunicacao', 'aspectos_capacitacao'], 'string'],
            [['periodicidade_monitoramentos', 'periodicidade_avaliacoes', 'periodicidade_atualizacoes'], 'string', 'max' => 255],
            [['objetivos_trabalhados', 'objetivos_estrategicos'], 'default', 'value' => null],
            [['eixoTematicoIds'], 'each', 'rule' => ['integer']],
            [['created_at'], 'safe'],
            [['diagnostico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Diagnostico::className(), 'targetAttribute' => ['diagnostico_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE_PROGRAMA] = [
            'objetivos_trabalhados',
            'objetivos_estrategicos',
            'estrutura_governanca',
            'periodicidade_monitoramentos',
            'periodicidade_avaliacoes',
            'periodicidade_atualizacoes',
            'aspectos_comunicacao',
            'aspectos_capacitacao',
            'eixoTematicoIds'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'diagnostico_id' => 'Diagnostico ID',
            'descricao' => 'Quais os principais resultados obtidos pelo diagnóstico do ambiente de integridade?',
            'objetivos_trabalhados' => 'Qual a visão de futuro em relação ao ambiente de integridade da organização?',
            'objetivos_estrategicos' => 'Quais os objetivos do programa de integridade da organização?',
            'estrutura_governanca' => 'Qual a estrutura de governança e de gestão do programa de integridade?',
            'periodicidade_monitoramentos' => 'Qual a periodicidade dos monitoramentos do programa de integridade?',
            'periodicidade_avaliacoes' => 'Qual a periodicidade das avaliações do programa de integridade?',
            'periodicidade_atualizacoes' => 'Qual a periodicidade das atualizações do programa de integridade?',
            'aspectos_comunicacao' => 'Quais os principais aspectos a serem observados na elaboração do plano de comunicação?',
            'aspectos_capacitacao' => 'Quais os principais aspectos a serem observados na elaboração do plano de capacitação?',
            'eixoTematicoIds' => 'Quais eixos temáticos serão trabalhados no programa de integridade da organização?',
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
