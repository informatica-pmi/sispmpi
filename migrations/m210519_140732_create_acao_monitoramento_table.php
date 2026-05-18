<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acao_monitoramento}}`.
 */
class m210519_140732_create_acao_monitoramento_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acao_monitoramento}}', [
            'id' => $this->primaryKey(),
            'risco_n_implementacao' => $this->tinyInteger()->notNull(),
            'acao_id' => $this->integer()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-acao_monitoramento-acao_id', 'acao_monitoramento', 'acao_id');

        $this->addForeignKey(
            'fk-acao_monitoramento-acao_id', 
            'acao_monitoramento', 
            'acao_id', 
            'acao', 
            'id', 
            'CASCADE'
        );

        $this->createIndex('idx-acao_monitoramento-usuario_id', 'acao_monitoramento', 'usuario_id');

        $this->addForeignKey(
            'fk-acao_monitoramento-usuario_id', 
            'acao_monitoramento', 
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
        $this->dropForeignKey('fk-acao_monitoramento-usuario_id', 'acao_monitoramento');

        $this->dropIndex('idx-acao_monitoramento-usuario_id', 'acao_monitoramento');

        $this->dropForeignKey('fk-acao_monitoramento-acao_id', 'acao_monitoramento');

        $this->dropIndex('idx-acao_monitoramento-acao_id', 'acao_monitoramento');

        $this->dropTable('{{%acao_monitoramento}}');
    }
}
