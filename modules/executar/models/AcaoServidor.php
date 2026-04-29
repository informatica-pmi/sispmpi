<?php

namespace app\modules\executar\models;

use Yii;
use app\models\Acao;
use app\models\Servidor;
use app\models\Historico;
use app\behaviors\AuditBehaviors;

/**
 * This is the model class for table "acao_servidor".
 *
 * @property int $id
 * @property int $acao_id
 * @property int $servidor_id
 * @property int $tipo
 *
 * @property Acao $acao
 * @property Servidor $servidor
 */
class AcaoServidor extends \yii\db\ActiveRecord
{
    public $saveAudit;

    public const TIPO_RESPONSAVEL = 1;
    public const TIPO_ENVOLVIDO = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_servidor';
    }

    public function behaviors()
    {
        return [
            'auditBehaviors' => [
                'class' => AuditBehaviors::className(),
                'extraFields' => ['saveAudit'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acao_id', 'servidor_id', 'tipo'], 'required'],
            [['acao_id', 'servidor_id', 'tipo', 'saveAudit'], 'integer'],
            [['acao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_id' => 'id']],
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
            'acao_id' => 'Acao ID',
            'servidor_id' => 'Servidor ID',
            'tipo' => 'Tipo',
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
     * Gets query for [[Servidor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServidor()
    {
        return $this->hasOne(Servidor::className(), ['id' => 'servidor_id']);
    }

    public function getHistoricos()
    {
        return $this->hasMany(Historico::className(), ['id_registro' => 'id'])
            ->onCondition(['model' => 'AcaoServidor']);
    }
}
