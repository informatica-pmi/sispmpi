<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "orgao_contabil".
 *
 * @property int $id
 * @property string $ano
 * @property float $orcamento
 * @property int $quantitativo_pessoal
 * @property int $orgao_id
 * @property string $created_at
 *
 * @property Orgao $orgao
 */
class OrgaoContabil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orgao_contabil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ano', 'orcamento', 'quantitativo_pessoal'], 'required'],
            [['created_at'], 'safe'],
            [['orcamento'], 'number', 'min' => 1],
            [['quantitativo_pessoal', 'orgao_id', 'ano'], 'integer'],
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
            'ano' => 'Ano',
            'orcamento' => 'Orçamento',
            'quantitativo_pessoal' => 'Quantitativo de pessoal',
            'orgao_id' => 'Orgao ID',
            'created_at' => 'Created At',
        ];
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
