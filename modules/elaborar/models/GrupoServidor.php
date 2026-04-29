<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\Servidor;

/**
 * This is the model class for table "grupo_servidor".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $servidor_id
 * @property int $status
 * @property int $coordenador
 * @property int $order
 *
 * @property Grupo $grupo
 * @property Servidor $servidor
 */
class GrupoServidor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_servidor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['servidor_id', 'status', 'coordenador'], 'required'],
            [['grupo_id', 'servidor_id', 'status', 'coordenador', 'order'], 'integer'],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grupo::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['servidor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servidor::className(), 'targetAttribute' => ['servidor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grupo_id' => 'Grupo ID',
            'servidor_id' => 'Servidor ID',
            'status' => 'Status',
            'coordenador' => 'Coordenador',
            'order' => 'Order',
        ];
    }

    /**
     * Gets query for [[Grupo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(Grupo::className(), ['id' => 'grupo_id']);
    }

    /**
     * Gets query for [[Servidor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServidor()
    {
        return $this->hasOne(Servidor::className(), ['id' => 'servidor_id']);
    }
}
