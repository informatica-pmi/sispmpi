<?php

namespace app\models;

use Yii;
use app\modules\elaborar\models\GrupoServidor;
use app\behaviors\AuditBehaviors;
use app\modules\executar\models\AcaoServidor;

/**
 * This is the model class for table "servidor".
 *
 * @property int $id
 * @property string $nome
 * @property string $masp_matricula
 * @property int $unidade_administrativa_id
 * @property string $created_at
 *
 * @property AcaoServidor $acaoServidor
 * @property GrupoServidor[] $grupoServidors
 * @property UnidadeAdministrativa $unidadeAdministrativa
 */
class Servidor extends \yii\db\ActiveRecord
{
    public $coordenador;
    public $saveAudit;

    public const SCENARIO_EXECUTAR = 'executar';
    public const SCENARIO_REUNIAO = 'reuniao';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servidor';
    }

    public function behaviors()
    {
        return [
            'auditBehaviors' => [
                'class' => AuditBehaviors::className(),
                'extraFields' => ['saveAudit'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'masp_matricula', 'unidade_administrativa_id', 'coordenador'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['nome', 'masp_matricula', 'unidade_administrativa_id'], 'required', 'on' => self::SCENARIO_EXECUTAR],
            [['nome'], 'required', 'on' => self::SCENARIO_REUNIAO],
            [['unidade_administrativa_id', 'coordenador', 'saveAudit'], 'integer'],
            [['created_at'], 'safe'],
            [['nome', 'masp_matricula'], 'string', 'max' => 255],
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
            'nome' => 'Nome',
            'masp_matricula' => 'Masp/Matrícula',
            'unidade_administrativa_id' => 'Unidade Administrativa',
            'coordenador' => 'Coordenador',
            'created_at' => 'Created At',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_EXECUTAR] = [
            'nome',
            'masp_matricula',
            'unidade_administrativa_id',
        ];
        $scenarios[self::SCENARIO_REUNIAO] = ['nome'];
        return $scenarios;
    }

    /**
     * Gets query for [[AcaoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoServidor()
    {
        return $this->hasOne(AcaoServidor::className(), ['servidor_id' => 'id']);
    }

    /**
     * Gets query for [[GrupoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoServidors()
    {
        return $this->hasMany(GrupoServidor::className(), ['servidor_id' => 'id']);
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
