<?php

namespace app\modules\admin\models;

use Yii;

/**
 * @property int $orgao_id
 */
class AlterarPerfil extends \yii\base\Model
{
    public $orgao_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orgao_id'], 'required'],
            [['orgao_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'orgao_id' => 'Órgão'
        ];
    }
}
