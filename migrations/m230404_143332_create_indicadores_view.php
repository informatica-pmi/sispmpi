<?php

use app\models\Status;
use yii\db\Migration;

/**
 * Class m230404_143332_create_indicadores_view
 */
class m230404_143332_create_indicadores_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            "CREATE VIEW v_indicadores AS SELECT
            o.nome as orgao_nome,
            o.tipo as orgao_tipo,
            oc.quantitativo_pessoal as orgao_pessoal,
            oc.orcamento as orgao_orcamento,
            oc.ano as orgao_orcamento_ano,
            pi.id as plano_integridade_id,
            pi.status as plano_integridade_status,
            IF(gi.formalmente, gi.data_publicacao, gi.data_inicio) as plano_integridade_data_inicio,
            p.data_publicacao as plano_integridade_data_conclusao,
            pi.edicao as plano_integridade_edicao,
            pi.versao as plano_integridade_versao,
            COUNT(DISTINCT e.id) as eixos,
            COUNT(a.id) as acoes,
            (
                SELECT COUNT(*) FROM acao _a
                LEFT JOIN eixo _e ON _e.id = _a.eixo_id
                LEFT JOIN plano_integridade _pi ON _pi.id = _e.plano_integridade_id
                WHERE _e.plano_integridade_id = pi.id AND (
                    (_a.previsao_inicio < NOW() AND _a.status = :acaoNaoInicializada)
                    OR (_a.previsao_conclusao < NOW() AND _a.status NOT IN (:acaoConcluida, :acaoDescontinuada))
                )
            ) as acoes_atrasadas
          FROM
            orgao o
            LEFT JOIN plano_integridade pi ON pi.orgao_id = o.id
            LEFT JOIN grupo g ON g.plano_integridade_id = pi.id
            LEFT JOIN grupo_instituido gi ON gi.grupo_id = g.id AND gi.order = 0
            LEFT JOIN eixo e ON e.plano_integridade_id = pi.id
            LEFT JOIN publicacao p ON p.plano_integridade_id = pi.id
            LEFT JOIN orgao_contabil oc ON oc.orgao_id = o.id AND oc.ano = :year
            LEFT JOIN acao a ON a.eixo_id = e.id
          WHERE
            (pi.id IN (SELECT MAX(plano_integridade.id) FROM plano_integridade GROUP BY orgao_id) OR pi.id IS NULL)
            AND o.status = 1
        GROUP BY o.id",
            [
                ':year' => date('Y'),
                ':acaoNaoInicializada' => Status::ACAO_N_INICIALIZADA,
                ':acaoConcluida' => Status::ACAO_CONCLUIDA,
                ':acaoDescontinuada' => Status::ACAO_DESCONTINUADA
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP VIEW v_indicadores');
    }
}
