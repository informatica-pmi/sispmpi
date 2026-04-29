<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Orgao;
use app\modules\executar\models\AcaoTipo;

/**
 * This is the model class for table "tipo".
 *
 * @property int $id
 * @property string $nome
 * @property int|null $orgao_id
 * @property string $created_at
 *
 * @property AcaoTipo[] $acaoTipos
 * @property Orgao $orgao
 */
class Tipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['orgao_id'], 'integer'],
            [['created_at'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['orgao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['orgao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'orgao_id' => 'Orgao ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AcaoTipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoTipos()
    {
        return $this->hasMany(AcaoTipo::className(), ['tipo_id' => 'id']);
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
}
