<?php

namespace app\modules\executar\models;

use Yii;
use app\models\Acao;
use app\models\Tipo;

/**
 * This is the model class for table "acao_tipo".
 *
 * @property int $id
 * @property int $acao_id
 * @property int $tipo_id
 *
 * @property Acao $acao
 * @property Tipo $tipo
 */
class AcaoTipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acao_id', 'tipo_id'], 'required'],
            [['acao_id', 'tipo_id'], 'integer'],
            [['acao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_id' => 'id']],
            [['tipo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo::className(), 'targetAttribute' => ['tipo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acao_id' => 'Acao ID',
            'tipo_id' => 'Tipo ID',
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
     * Gets query for [[Tipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(Tipo::className(), ['id' => 'tipo_id']);
    }
}
