<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Orgao;
use app\modules\executar\models\AcaoExecucaoFatorLimitante;

/**
 * This is the model class for table "fator_limitante".
 *
 * @property int $id
 * @property string $nome
 * @property int|null $orgao_id
 * @property string $created_at
 *
 * @property AcaoExecucaoFatorLimitante[] $acaoExecucaoFatorLimitantes
 * @property Orgao $orgao
 */
class FatorLimitante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fator_limitante';
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
            'orgao_id' => 'Orgão',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AcaoExecucaoFatorLimitantes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucaoFatorLimitantes()
    {
        return $this->hasMany(AcaoExecucaoFatorLimitante::className(), ['fator_limitante_id' => 'id']);
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
