<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acao_avaliacao_recomendacao".
 *
 * @property int $id
 * @property string $recomendacao
 * @property string|null $resposta
 * @property int $acao_id
 * @property int $usuario_id
 * @property int|null $usuario_resposta_id
 * @property string $created_at
 *
 * @property Acao $acao
 * @property User $usuario
 * @property User $usuarioResposta
 */
class AcaoAvaliacaoRecomendacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_avaliacao_recomendacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recomendacao'], 'required'],
            [['recomendacao', 'resposta'], 'string'],
            [['resposta'], 'default', 'value' => null],
            [['acao_id', 'usuario_id', 'usuario_resposta_id'], 'integer'],
            [['created_at'], 'safe'],
            [['acao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_id' => 'id']],
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
            'recomendacao' => 'recomendação do controle interno',
            'resposta' => 'Resposta do executor da ação',
            'acao_id' => 'Acao ID',
            'usuario_id' => 'Usuario ID',
            'usuario_resposta_id' => 'Usuario Resposta ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Acao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcao()
    {
        return $this->hasOne(Acao::className(), ['id' => 'acao_id']);
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
