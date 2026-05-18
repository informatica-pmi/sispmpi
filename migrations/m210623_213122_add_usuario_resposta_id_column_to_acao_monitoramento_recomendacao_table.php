<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%acao_monitoramento_recomendacao}}`.
 */
class m210623_213122_add_usuario_resposta_id_column_to_acao_monitoramento_recomendacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('acao_monitoramento_recomendacao', 'usuario_resposta_id', $this->integer()->null()->after('usuario_id'));

        $this->createIndex(
            'idx-acao_monitoramento_recomendacao-usuario_resposta_id', 
            'acao_monitoramento_recomendacao', 
            'usuario_resposta_id'
        );

        $this->addForeignKey(
            'fk-acao_monitoramento_recomendacao-usuario_resposta_id', 
            'acao_monitoramento_recomendacao', 
            'usuario_resposta_id', 
            'usuario', 
            'id', 
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-acao_monitoramento_recomendacao-usuario_resposta_id', 'acao_monitoramento_recomendacao');

        $this->dropIndex('idx-acao_monitoramento_recomendacao-usuario_resposta_id', 'acao_monitoramento_recomendacao');

        $this->dropColumn('acao_monitoramento_recomendacao', 'usuario_resposta_id');
    }
}
