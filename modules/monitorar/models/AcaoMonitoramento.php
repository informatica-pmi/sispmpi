<?php

namespace app\modules\monitorar\models;

use Yii;
use app\models\Acao;
use app\models\User;
use app\models\AcaoMonitoramentoRecomendacao;

/**
 * This is the model class for table "acao_monitoramento".
 *
 * @property int $id
 * @property int $risco_n_implementacao
 * @property int $acao_id
 * @property int $usuario_id
 * @property string $created_at
 *
 * @property Acao $acao
 * @property User $usuario
 * @property AcaoMonitoramentoRecomendacao[] $acaoMonitoramentoRecomendacaos
 */
class AcaoMonitoramento extends \yii\db\ActiveRecord
{
    public const RISCO_BAIXO = 1;
    public const RISCO_MEDIO = 2;
    public const RISCO_ALTO = 3;
    public const RISCO_EXTREMO = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_monitoramento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['risco_n_implementacao', 'acao_id', 'usuario_id'], 'required'],
            [['risco_n_implementacao', 'acao_id', 'usuario_id'], 'integer'],
            [['created_at'], 'safe'],
            [['acao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_id' => 'id']],
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
            'risco_n_implementacao' => 'Risco de não implementação',
            'acao_id' => 'Acao ID',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Retorna array ou valor com o risco de não implementação da ação
     * @param integer|null $key
     * @return array|string
     */
    public static function getRisco($key = null)
    {
        $arr = [
            self::RISCO_BAIXO => 'Baixo',
            self::RISCO_MEDIO => 'Médio',
            self::RISCO_ALTO => 'Alto',
            self::RISCO_EXTREMO => 'Extremo',
        ];

        return is_null($key) ? $arr : $arr[$key];
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
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }

    /**
     * Gets query for [[AcaoMonitoramentoRecomendacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoMonitoramentoRecomendacaos()
    {
        return $this->hasMany(AcaoMonitoramentoRecomendacao::className(), ['acao_monitoramento_id' => 'id']);
    }
}
