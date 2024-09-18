<?php 

namespace app\components\helpers;

class Dynamic 
{
    /**
     * Alterando o scenario de determinado modelo dinamico [DynamicForm]
     * @param object $models Modelo que será alterado o scenario
     * @param string $scenario Scenario que será aplicado no modelo
     * @return bool
     */
    public static function scenario($models, $scenario)
    {
        foreach ($models as $model) {
            $model->scenario = $scenario;
        }

        return true;
    }
}