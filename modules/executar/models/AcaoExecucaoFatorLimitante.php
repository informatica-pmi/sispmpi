<?php

namespace app\modules\executar\models;

use Yii;
use app\models\FatorLimitante;

/**
 * This is the model class for table "acao_execucao_fator_limitante".
 *
 * @property int $id
 * @property int $fator_limitante_id
 * @property int $acao_execucao_id
 *
 * @property AcaoExecucao $acaoExecucao
 * @property FatorLimitante $fatorLimitante
 */
class AcaoExecucaoFatorLimitante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_execucao_fator_limitante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fator_limitante_id', 'acao_execucao_id'], 'required'],
            [['fator_limitante_id', 'acao_execucao_id'], 'integer'],
            [['acao_execucao_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcaoExecucao::className(), 'targetAttribute' => ['acao_execucao_id' => 'id']],
            [['fator_limitante_id'], 'exist', 'skipOnError' => true, 'targetClass' => FatorLimitante::className(), 'targetAttribute' => ['fator_limitante_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fator_limitante_id' => 'Fator Limitante ID',
            'acao_execucao_id' => 'Acao Execucao ID',
        ];
    }

    /**
     * Gets query for [[AcaoExecucao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucao()
    {
        return $this->hasOne(AcaoExecucao::className(), ['id' => 'acao_execucao_id']);
    }

    /**
     * Gets query for [[FatorLimitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatorLimitante()
    {
        return $this->hasOne(FatorLimitante::className(), ['id' => 'fator_limitante_id']);
    }
}
