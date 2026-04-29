<?php

namespace app\modules\monitorar\models;

use Yii;
use app\models\Servidor;

/**
 * This is the model class for table "reuniao_servidor".
 *
 * @property int $id
 * @property int $reuniao_id
 * @property int $servidor_id
 *
 * @property Reuniao $reuniao
 * @property Servidor $servidor
 */
class ReuniaoServidor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reuniao_servidor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reuniao_id', 'servidor_id'], 'required'],
            [['reuniao_id', 'servidor_id'], 'integer'],
            [['reuniao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reuniao::className(), 'targetAttribute' => ['reuniao_id' => 'id']],
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
            'reuniao_id' => 'Reuniao ID',
            'servidor_id' => 'Servidor ID',
        ];
    }

    /**
     * Gets query for [[Reuniao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReuniao()
    {
        return $this->hasOne(Reuniao::className(), ['id' => 'reuniao_id']);
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
