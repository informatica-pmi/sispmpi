<?php

namespace app\models;

class Status
{
    public const STATUS_ATIVO = 1;
    public const STATUS_INATIVO = 0;

    public const STATUS_SIM = 1;
    public const STATUS_NAO = 0;

    public const PLANO_ELABORACAO = 1;
    public const PLANO_PUBLICADO = 2;
    public const PLANO_N_INICIADO = 3;
    public const PLANO_OBSOLETO = 4;

    public const ACAO_N_INICIALIZADA = 1;
    public const ACAO_EM_ANDAMENTO = 2;
    public const ACAO_CONCLUIDA = 3;
    public const ACAO_DESCONTINUADA = 4;

    /**
     * Retornar array ou valor dos status padrões
     * @param integer|null $key
     * @return array|string
     */
    public static function getPadrao($key = null)
    {
        $arr = [
            self::STATUS_ATIVO => 'Ativo',
            self::STATUS_INATIVO => 'Inativo'
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Retornar array ou valor das respostas padrões
     * @param integer|null $key
     * @return array|string
     */
    public static function getResposta($key = null)
    {
        $arr = [
            self::STATUS_SIM => 'Sim',
            self::STATUS_NAO => 'Não'
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Retornar array ou valor com os status das ações
     * @param integer|null $key
     * @return array|string
     */
    public static function getAcaoStatus($key = null)
    {
        $arr = [
            self::ACAO_N_INICIALIZADA => 'Não inicializada',
            self::ACAO_EM_ANDAMENTO => 'Em andamento',
            self::ACAO_CONCLUIDA => 'Concluída',
            self::ACAO_DESCONTINUADA => 'Descontinuada',
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Retornar array ou valor com os status do plano
     * @param integer|null $key
     * @return array|string
     */
    public static function getPlanoStatus($key = null)
    {
        $arr = [
            self::PLANO_ELABORACAO => 'Em elaboração',
            self::PLANO_N_INICIADO => 'Não iniciado',
            self::PLANO_PUBLICADO => 'Publicado'
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Retornar array ou valor com os status do plano
     * @param integer|null $key
     * @return array|string
     */
    public static function getPlanoStatusChart($key = null)
    {
        $arr = [
            self::PLANO_ELABORACAO => 'Em elaboração',
            self::PLANO_N_INICIADO => 'Não iniciados',
            self::PLANO_PUBLICADO => 'Publicados'
        ];

        return is_null($key) ? $arr : $arr[$key];
    }
}
