<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%acao_execucao}}`.
 */
class m210908_170536_drop_evidencia_arquivo_column_from_acao_execucao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-acao_execucao-arquivo_id', 'acao_execucao');

        $this->dropIndex('idx-acao_execucao-arquivo_id', 'acao_execucao');

        $this->dropColumn('acao_execucao', 'evidencia_arquivo');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('acao_execucao', 'evidencia_arquivo', $this->integer()->null()->after('evidencias_sugeridas'));

        $this->createIndex(
            'idx-acao_execucao-evidencia_arquivo', 
            'acao_execucao', 
            'evidencia_arquivo'
        );

        $this->addForeignKey(
            'fk-acao_execucao-evidencia_arquivo', 
            'acao_execucao', 'evidencia_arquivo', 
            'arquivo', 
            'id', 
            'CASCADE'
        );
    }
}
