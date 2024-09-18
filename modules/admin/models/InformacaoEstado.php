<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "informacao_estado".
 *
 * @property int $id
 * @property string $ano
 * @property float $orcamento
 * @property int $quantitativo_pessoal
 */
class InformacaoEstado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informacao_estado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ano', 'orcamento', 'quantitativo_pessoal'], 'required'],
            [['orcamento'], 'number', 'min' => 1],
            [['quantitativo_pessoal', 'ano'], 'integer'],
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
        ];
    }
}
