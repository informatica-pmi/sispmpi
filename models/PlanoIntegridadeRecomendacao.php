<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "plano_integridade_recomendacao".
 *
 * @property int $id
 * @property string $recomendacao
 * @property string|null $resposta
 * @property int $plano_integridade_id
 * @property int $usuario_id
 * @property int|null $usuario_resposta_id
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property Usuario $usuario
 * @property Usuario $usuarioResposta
 */
class PlanoIntegridadeRecomendacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plano_integridade_recomendacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recomendacao'], 'required'],
            [['recomendacao', 'resposta'], 'string'],
            [['plano_integridade_id', 'usuario_id', 'usuario_resposta_id'], 'integer'],
            [['created_at'], 'safe'],
            [['resposta'], 'default', 'value' => null],
            [['plano_integridade_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['usuario_resposta_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_resposta_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recomendacao' => 'Recomendação',
            'resposta' => 'Resposta',
            'plano_integridade_id' => 'Plano Integridade ID',
            'usuario_id' => 'Usuario ID',
            'usuario_resposta_id' => 'Usuario Resposta ID',
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
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }

    /**
     * Gets query for [[UsuarioResposta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioResposta()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_resposta_id']);
    }
}
