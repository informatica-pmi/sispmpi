<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Orgao;
use app\modules\avaliar\models\Promover;
use app\modules\elaborar\models\Grupo;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\Validacao;
use app\modules\elaborar\models\Eixo;
use app\modules\elaborar\models\Publicacao;
use app\modules\monitorar\models\Reuniao;

/**
 * This is the model class for table "plano_integridade".
 *
 * @property int $id
 * @property string $edicao
 * @property float $versao
 * @property int $status
 * @property int $orgao_id
 * @property int|null $plano_integridade_referencia_id
 * @property string $created_at
 *
 * @property Diagnostico $diagnostico
 * @property Eixo[] $eixos
 * @property Grupo $grupo
 * @property Orgao $orgao
 * @property Publicacao $publicacao
 * @property Validacao $validacao
 * @property Reuniao[] $reuniaos
 * @property PlanoIntegridadeRecomendacao[] $planoIntegridadeRecomendacaos
 * @property PlanoIntegridadeNovo[] $planoIntegridadeNovos
 * @property PlanoIntegridade $planoIntegridadeReferencia
 * @property Promover[] $promoverIntegridades
 */
class PlanoIntegridade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plano_integridade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['edicao', 'status', 'orgao_id'], 'required'],
            [['versao'], 'number'],
            [['status', 'orgao_id', 'plano_integridade_referencia_id'], 'integer'],
            [['created_at'], 'safe'],
            [['edicao'], 'string', 'max' => 255],
            [['orgao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['orgao_id' => 'id']],
            [['plano_integridade_referencia_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_referencia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'edicao' => 'Edicao',
            'versao' => 'Versao',
            'status' => 'Status',
            'orgao_id' => 'Orgao ID',
            'plano_integridade_referencia_id' => 'Plano Integridade Referencia ID',
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
        return $this->hasOne(Diagnostico::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[Eixos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEixos()
    {
        return $this->hasMany(Eixo::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[Grupo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(Grupo::className(), ['plano_integridade_id' => 'id']);
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
     * Gets query for [[PromoverIntegridades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPromoverIntegridades()
    {
        return $this->hasMany(Promover::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[Publicacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicacao()
    {
        return $this->hasOne(Publicacao::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[Validacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValidacao()
    {
        return $this->hasOne(Validacao::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[Reuniaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReuniaos()
    {
        return $this->hasMany(Reuniao::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[PlanoIntegridadeRecomendacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridadeRecomendacaos()
    {
        return $this->hasMany(PlanoIntegridadeRecomendacao::className(), ['plano_integridade_id' => 'id'])
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Gets query for [[PlanoIntegridadeNovos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridadeNovos()
    {
        return $this->hasMany(PlanoIntegridadeNovo::className(), ['plano_integridade_id' => 'id']);
    }

    /**
     * Gets query for [[PlanoIntegridadeReferencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridadeReferencia()
    {
        return $this->hasOne(PlanoIntegridade::className(), ['id' => 'plano_integridade_referencia_id']);
    }
}
