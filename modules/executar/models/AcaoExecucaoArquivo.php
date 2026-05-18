<?php

namespace app\modules\executar\models;

use Yii;
use app\models\Arquivo;

/**
 * This is the model class for table "acao_execucao_arquivo".
 *
 * @property int $id
 * @property int $acao_execucao_id
 * @property int $arquivo_id
 *
 * @property AcaoExecucao $acaoExecucao
 * @property Arquivo $arquivo
 */
class AcaoExecucaoArquivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_execucao_arquivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['acao_execucao_id', 'arquivo_id'], 'required'],
            [['acao_execucao_id', 'arquivo_id'], 'integer'],
            [['acao_execucao_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcaoExecucao::className(), 'targetAttribute' => ['acao_execucao_id' => 'id']],
            [['arquivo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Arquivo::className(), 'targetAttribute' => ['arquivo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acao_execucao_id' => 'Acao Execucao ID',
            'arquivo_id' => 'Arquivo ID',
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
     * Gets query for [[Arquivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArquivo()
    {
        return $this->hasOne(Arquivo::className(), ['id' => 'arquivo_id']);
    }
}
