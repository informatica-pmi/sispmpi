<?php

namespace app\models;

use Yii;
use app\modules\admin\models\Orgao;
use app\modules\elaborar\models\ValidacaoStakeholder;

/**
 * This is the model class for table "stakeholder".
 *
 * @property int $id
 * @property string $nome
 * @property int|null $orgao_id
 * @property string $created_at
 *
 * @property Orgao $orgao
 * @property ValidacaoStakeholder[] $validacaoStakeholders
 */
class Stakeholder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stakeholder';
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
     * Gets query for [[Orgao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgao()
    {
        return $this->hasOne(Orgao::className(), ['id' => 'orgao_id']);
    }

    /**
     * Gets query for [[ValidacaoStakeholders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValidacaoStakeholders()
    {
        return $this->hasMany(ValidacaoStakeholder::className(), ['stakeholder_id' => 'id']);
    }
}
