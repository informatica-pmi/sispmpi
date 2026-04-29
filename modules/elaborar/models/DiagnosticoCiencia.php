<?php

namespace app\modules\elaborar\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "diagnostico_ciencia".
 *
 * @property int $id
 * @property int $diagnostico_id
 * @property string|null $sugestoes
 * @property int $validado
 * @property int|null $usuario_id
 * @property string $created_at
 *
 * @property Diagnostico $diagnostico
 * @property User $usuario
 */
class DiagnosticoCiencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diagnostico_ciencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diagnostico_id'], 'required'],
            [['diagnostico_id', 'validado', 'usuario_id'], 'integer'],
            [['sugestoes'], 'string'],
            [['created_at'], 'safe'],
            [['sugestoes'], 'default', 'value' => null],
            [['diagnostico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Diagnostico::className(), 'targetAttribute' => ['diagnostico_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'diagnostico_id' => 'Diagnostico ID',
            'sugestoes' => 'Sugestões da alta administração',
            'validado' => 'Validado',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Diagnostico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnostico()
    {
        return $this->hasOne(Diagnostico::className(), ['id' => 'diagnostico_id']);
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
}
