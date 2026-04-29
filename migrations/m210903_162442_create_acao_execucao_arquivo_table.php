<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acao_execucao_arquivo}}`.
 */
class m210903_162442_create_acao_execucao_arquivo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acao_execucao_arquivo}}', [
            'id' => $this->primaryKey(),
            'acao_execucao_id' => $this->integer()->notNull(),
            'arquivo_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-acao_execucao_arquivo-acao_execucao_id', 
            'acao_execucao_arquivo', 
            'acao_execucao_id'
        );

        $this->addForeignKey(
            'fk-acao_execucao_arquivo-acao_execucao_id', 
            'acao_execucao_arquivo', 
            'acao_execucao_id', 
            'acao_execucao', 
            'id', 
            'CASCADE'
        );

        $this->createIndex('idx-acao_execucao_arquivo-arquivo_id', 'acao_execucao_arquivo', 'acao_execucao_id');

        $this->addForeignKey(
            'fk-acao_execucao_arquivo-arquivo_id', 
            'acao_execucao_arquivo', 
            'arquivo_id', 
            'arquivo', 
            'id', 
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-acao_execucao_arquivo-arquivo_id', 'acao_execucao_arquivo');

        $this->dropIndex('idx-acao_execucao_arquivo-arquivo_id', 'acao_execucao_arquivo');

        $this->dropForeignKey('fk-acao_execucao_arquivo-acao_execucao_id', 'acao_execucao_arquivo');

        $this->dropIndex('idx-acao_execucao_arquivo-acao_execucao_id', 'acao_execucao_arquivo');
        
        $this->dropTable('{{%acao_execucao_arquivo}}');
    }
}
