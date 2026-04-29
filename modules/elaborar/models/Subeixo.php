<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\Acao;

/**
 * This is the model class for table "subeixo".
 *
 * @property int $id
 * @property int $eixo_id
 * @property string $titulo
 * @property string $descricao
 * @property string $created_at
 *
 * @property Acao[] $acaos
 * @property Eixo $eixo
 */
class Subeixo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subeixo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eixo_id', 'titulo', 'descricao'], 'required'],
            [['eixo_id'], 'integer'],
            [['descricao'], 'string'],
            [['created_at'], 'safe'],
            [['titulo'], 'string', 'max' => 255],
            [['eixo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eixo::className(), 'targetAttribute' => ['eixo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eixo_id' => 'Eixo',
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
        return $this->hasMany(Acao::className(), ['subeixo_id' => 'id']);
    }

    /**
     * Gets query for [[Eixo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEixo()
    {
        return $this->hasOne(Eixo::className(), ['id' => 'eixo_id']);
    }
}
