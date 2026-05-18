<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\PlanoIntegridade;
use app\models\Acao;

/**
 * This is the model class for table "eixo".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property string $titulo
 * @property string $descricao
 * @property string $created_at
 *
 * @property Acao[] $acaos
 * @property PlanoIntegridade $planoIntegridade
 * @property Subeixo[] $subeixos
 */
class Eixo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eixo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'titulo', 'descricao'], 'required'],
            [['plano_integridade_id'], 'integer'],
            [['descricao'], 'string'],
            [['created_at'], 'safe'],
            [['titulo'], 'string', 'max' => 255],
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
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Acaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaos()
    {
        return $this->hasMany(Acao::className(), ['eixo_id' => 'id']);
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
     * Gets query for [[Subeixos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubeixos()
    {
        return $this->hasMany(Subeixo::className(), ['eixo_id' => 'id']);
    }
}
