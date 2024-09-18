<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historico".
 *
 * @property int $id
 * @property string $action
 * @property string $model
 * @property int $id_registro
 * @property string $campo
 * @property string $antigo_valor
 * @property string $novo_valor
 * @property string|null $justificativa
 * @property int|null $multiple
 * @property int $usuario_id
 * @property string|null $usuario_perfil
 * @property string $created_at
 *
 * @property User $usuario
 */
class Historico extends \yii\db\ActiveRecord
{
    public const ACTION_CREATE_UPDATE = 'Create/Update';
    public const ACTION_DELETE = 'Delete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'action', 'id_registro', 'campo', 'usuario_id', 'usuario_perfil'], 'required'],
            [['id_registro', 'multiple', 'usuario_id'], 'integer'],
            [['antigo_valor', 'novo_valor', 'justificativa', 'action'], 'string'],
            [['created_at'], 'safe'],
            [['antigo_valor', 'novo_valor', 'justificativa'], 'default', 'value' => null],
            [['model', 'campo'], 'string', 'max' => 255],
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
            'action' => 'Action',
            'model' => 'Model',
            'id_registro' => 'Id Registro',
            'campo' => 'Campo',
            'antigo_valor' => 'Antigo Valor',
            'novo_valor' => 'Novo Valor',
            'justificativa' => 'Justificativa',
            'multiple' => 'Multiple',
            'usuario_id' => 'Usuario ID',
            'usuario_perfil' => 'Perfil',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }
}
