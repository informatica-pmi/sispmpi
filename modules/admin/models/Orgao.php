<?php

namespace app\modules\admin\models;

use Yii;
use app\models\Instrumento;
use app\models\PlanoIntegridade;
use app\models\Stakeholder;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\modules\avaliar\models\Promover;

/**
 * This is the model class for table "orgao".
 *
 * @property int $id
 * @property string $nome
 * @property string $sigla
 * @property int $tipo
 * @property int $status
 * @property string $created_at
 *
 * @property Instrumento[] $instrumentos
 * @property OrgaoContabil[] $orgaoContabils
 * @property OrgaoContabil $currentOrgaoContabil
 * @property PlanoIntegridade[] $planoIntegridades
 * @property PlanoIntegridade $endPlanoIntegridade
 * @property Promover[] $promoverIntegridades
 * @property Stakeholder[] $stakeholders
 * @property UnidadeAdministrativa[] $unidadeAdministrativas
 * @property User[] $usuarios
 */
class Orgao extends \yii\db\ActiveRecord
{
    public const ORGAO_CGE = 17;

    public const TIPO_AUTONOMO = 1;
    public const TIPO_AUTARQUIA = 2;
    public const TIPO_FUNDACAO = 3;
    public const TIPO_SEC_ESTADO = 4;
    public const TIPO_EMPRESAS_ESTATAIS = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orgao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'sigla', 'tipo', 'status'], 'required'],
            [['tipo', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['sigla'], 'string', 'max' => 10],
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
            'sigla' => 'Sigla',
            'tipo' => 'Tipo',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Montando o array com os tipos de órgãos permitidos
     *
     * @param integer|null $key
     * @return array|string
     */
    public static function getTipo($key = null)
    {
        $arr = [
            self::TIPO_AUTONOMO => 'Prefeitura Municipal',
            self::TIPO_AUTARQUIA => 'Autarquia',
            self::TIPO_FUNDACAO => 'Fundação',
            self::TIPO_SEC_ESTADO => 'Secretaria de Estado',
            self::TIPO_EMPRESAS_ESTATAIS => 'Empresas Municipais'
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Gets query for [[Instrumentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstrumentos()
    {
        return $this->hasMany(Instrumento::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[OrgaoContabils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgaoContabils()
    {
        return $this->hasMany(OrgaoContabil::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[OrgaoContabils]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentOrgaoContabil()
    {
        return $this->hasOne(OrgaoContabil::className(), ['orgao_id' => 'id'])
            ->where(['ano' => date('Y')]);
    }

    /**
     * Gets query for [[PlanoIntegridades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridades()
    {
        return $this->hasMany(PlanoIntegridade::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[PlanoIntegridades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEndPlanoIntegridade()
    {
        return $this->hasOne(PlanoIntegridade::className(), ['orgao_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

     /**
     * Gets query for [[PromoverIntegridades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPromoverIntegridades()
    {
        return $this->hasMany(Promover::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[Stakeholders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStakeholders()
    {
        return $this->hasMany(Stakeholder::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[UnidadeAdministrativas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadeAdministrativas()
    {
        return $this->hasMany(UnidadeAdministrativa::className(), ['orgao_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(User::className(), ['orgao_id' => 'id']);
    }
}
