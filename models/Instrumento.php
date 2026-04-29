<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Orgao;
use app\modules\elaborar\models\DiagnosticoInstrumento;

/**
 * This is the model class for table "instrumento".
 *
 * @property int $id
 * @property string $nome
 * @property int|null $orgao_id
 * @property string $created_at
 *
 * @property DiagnosticoInstrumento[] $diagnosticoInstrumentos
 * @property Orgao $orgao
 */
class Instrumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instrumento';
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
            'orgao_id' => 'Órgão',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[DiagnosticoInstrumentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticoInstrumentos()
    {
        return $this->hasMany(DiagnosticoInstrumento::className(), ['instrumento_id' => 'id']);
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
