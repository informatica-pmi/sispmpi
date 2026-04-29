<?php

namespace app\components\helpers;

use Yii;
use yii\helpers\ArrayHelper;

use app\models\User;

class OrganSpecific 
{
    /**
     * @param string $model instace of class for search register specific of organ
     * @param string $key value to save at database
     * @param string $label value to show user
     */
    public static function search($model, $key = 'id', $label = 'nome')
    {
        $options = ArrayHelper::map(
            ArrayHelper::getColumn(
                $model::find()
                    ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                    ->orWhere(['orgao_id' => null])
                    ->orderBy([$label => 'SORT_ASC'])
                    ->all(),
                function ($register) use ($key, $label) {
                    return is_null($register['orgao_id']) ? 
                        [$key => $register[$key], $label => $register[$label]] :
                        [
                            $key => $register[$key], 
                            $label => $register[$label] . " <span class='badge badge-info'>Específico do órgão</span>",
                        ];
                }),
            $key,
            $label,
        );

        return $options;
    }
}