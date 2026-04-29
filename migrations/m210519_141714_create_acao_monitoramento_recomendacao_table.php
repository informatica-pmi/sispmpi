<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acao_monitoramento_recomendacao}}`.
 */
class m210519_141714_create_acao_monitoramento_recomendacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acao_monitoramento_recomendacao}}', [
            'id' => $this->primaryKey(),
            'recomendacao' => $this->text()->notNull(),
            'resposta' => $this->text()->null(),
            'acao_monitoramento_id' => $this->integer()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-acao_monitoramento_recomendacao-acao_monitoramento_id', 
            'acao_monitoramento_recomendacao', 
            'acao_monitoramento_id'
        );

        $this->addForeignKey(
            'fk-acao_monitoramento_recomendacao-acao_monitoramento_id', 
            'acao_monitoramento_recomendacao', 
            'acao_monitoramento_id', 
            'acao_monitoramento', 
            'id', 
            'CASCADE'
        );

        $this->createIndex(
            'idx-acao_monitoramento_recomendacao-usuario_id', 
            'acao_monitoramento_recomendacao', 
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-acao_monitoramento_recomendacao-usuario_id', 
            'acao_monitoramento_recomendacao', 
            'usuario_id', 
            'usuario', 
            'id', 
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-acao_monitoramento_recomendacao-usuario_id', 'acao_monitoramento_recomendacao');

        $this->dropIndex('idx-acao_monitoramento_recomendacao-usuario_id', 'acao_monitoramento_recomendacao');

        $this->dropForeignKey('fk-acao_monitoramento_recomendacao-acao_monitoramento_id', 'acao_monitoramento_recomendacao');

        $this->dropIndex('idx-acao_monitoramento_recomendacao-acao_monitoramento_id', 'acao_monitoramento_recomendacao');

        $this->dropTable('{{%acao_monitoramento_recomendacao}}');
    }
}
