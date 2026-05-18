<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\PlanoIntegridade;

/**
 * This is the model class for table "grupo".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property GrupoInstituido[] $grupoInstituidos
 * @property GrupoInstituido $firstGrupoInstituido
 * @property GrupoInstituido[] $withoutFirstGrupoInstituido
 * @property GrupoServidor[] $grupoServidors
 * @property GrupoServidor[] $firstGrupoServidors
 * @property GrupoServidor[] $withoutFirstGrupoServidors
 */
class Grupo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id'], 'required'],
            [['plano_integridade_id'], 'integer'],
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
     * Gets query for [[GrupoInstituidos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoInstituidos()
    {
        return $this->hasMany(GrupoInstituido::className(), ['grupo_id' => 'id']);
    }

    /**
     * Gets query for [[firstGrupoInstituido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFirstGrupoInstituido()
    {
        return $this->hasOne(GrupoInstituido::className(), ['grupo_id' => 'id'])
            ->where(['order' => 0]);
    }

    /**
     * Gets query for [[firstGrupoInstituido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWithoutFirstGrupoInstituido()
    {
        return $this->hasMany(GrupoInstituido::className(), ['grupo_id' => 'id'])
            ->where(['!=', 'order', 0]);
    }

    /**
     * Gets query for [[GrupoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoServidors()
    {
        return $this->hasMany(GrupoServidor::className(), ['grupo_id' => 'id']);
    }

    /**
     * Gets query for [[GrupoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFirstGrupoServidors()
    {
        return $this->hasMany(GrupoServidor::className(), ['grupo_id' => 'id'])
            ->where(['order' => 0]);
    }

    /**
     * Gets query for [[GrupoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWithoutFirstGrupoServidors()
    {
        return $this->hasMany(GrupoServidor::className(), ['grupo_id' => 'id'])
            ->where(['!=', 'order', 0]);
    }
}
