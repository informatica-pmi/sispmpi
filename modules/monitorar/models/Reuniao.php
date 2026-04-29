<?php

namespace app\modules\monitorar\models;

use Yii;
use app\models\PlanoIntegridade;
use app\models\User;

/**
 * This is the model class for table "reuniao".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property string $data
 * @property string $nome
 * @property string $pauta
 * @property string $registro
 * @property int $usuario_id
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property User $usuario
 * @property ReuniaoServidor[] $reuniaoServidors
 */
class Reuniao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reuniao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'data', 'nome', 'pauta', 'registro', 'usuario_id'], 'required'],
            [['plano_integridade_id', 'usuario_id'], 'integer'],
            [['data', 'created_at'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['pauta'], 'string', 'max' => 1000],
            [['registro'], 'string', 'max' => 20000],
            [['plano_integridade_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_id' => 'id']],
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
            'plano_integridade_id' => 'Plano Integridade ID',
            'data' => 'Data',
            'nome' => 'Nome da reunião',
            'pauta' => 'Pauta',
            'registro' => 'Registro',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PlanoIntegridade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridade()
    {
        return $this->hasOne(PlanoIntegridade::className(), ['id' => 'plano_integridade_id']);
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
     * Gets query for [[ReuniaoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReuniaoServidors()
    {
        return $this->hasMany(ReuniaoServidor::className(), ['reuniao_id' => 'id']);
    }
}
