<?php

namespace app\components\helpers;

class TabsHeader
{
    /**
     * Preparando tabs para ser usado nas views de diagnostico
     *
     * @param $defaultPermission
     * @return array
     */
    public static function generateDiagnostico($defaultPermission)
    {
        $prepareTabs = [
            'informacoes-estrategicas' => [
                'pageKey' => 0,
                'label' => '1 - Estrutura organizacional',
                'hasPermission' => $defaultPermission,
            ],
            'instrumentos' => [
                'pageKey' => 1,
                'label' => '2 - Diagnóstico do ambiente de integridade',
                'hasPermission' => $defaultPermission,
            ],
            'resultados-diagnostico' => [
                'pageKey' => 2,
                'label' => '3 - Programa de integridade',
                'hasPermission' => $defaultPermission,
            ]
        ];

        return $prepareTabs;
    }
}
