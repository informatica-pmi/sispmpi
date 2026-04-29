<?php

namespace app\models;

use Yii;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * This is the model class for table "acao_monitoramento_recomendacao".
 *
 * @property int $id
 * @property string $recomendacao
 * @property string|null $resposta
 * @property int $acao_monitoramento_id
 * @property int $usuario_id
 * @property int|null $usuario_resposta_id
 * @property string $created_at
 *
 * @property AcaoMonitoramento $acaoMonitoramento
 * @property User $usuario
 * @property User $usuarioResposta
 */
class AcaoMonitoramentoRecomendacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_monitoramento_recomendacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recomendacao'], 'required'],
            [['recomendacao', 'resposta'], 'string'],
            [['acao_monitoramento_id', 'usuario_id', 'usuario_resposta_id'], 'integer'],
            [['resposta'], 'default', 'value' => null],
            [['created_at'], 'safe'],
            [['acao_monitoramento_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcaoMonitoramento::className(), 'targetAttribute' => ['acao_monitoramento_id' => 'id']],
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
            'recomendacao' => 'recomendação do comitê de monitoramento',
            'resposta' => 'Resposta do executor da ação',
            'acao_monitoramento_id' => 'Acao Monitoramento ID',
            'usuario_id' => 'Usuario ID',
            'usuario_resposta_id' => 'Usuario Resposta ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AcaoMonitoramento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoMonitoramento()
    {
        return $this->hasOne(AcaoMonitoramento::className(), ['id' => 'acao_monitoramento_id']);
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
