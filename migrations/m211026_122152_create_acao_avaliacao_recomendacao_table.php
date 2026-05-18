<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acao_avaliacao_recomendacao}}`.
 */
class m211026_122152_create_acao_avaliacao_recomendacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acao_avaliacao_recomendacao}}', [
            'id' => $this->primaryKey(),
            'recomendacao' => $this->text()->notNull(),
            'resposta' => $this->text()->null(),
            'acao_id' => $this->integer()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'usuario_resposta_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex('idx-acao_avaliacao_recomendacao-acao_id', 'acao_avaliacao_recomendacao', 'acao_id');

        $this->addForeignKey(
            'fk-acao_avaliacao_recomendacao-acao_id',
            'acao_avaliacao_recomendacao',
            'acao_id',
            'acao',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-acao_avaliacao_recomendacao-usuario_id', 'acao_avaliacao_recomendacao', 'usuario_id');

        $this->addForeignKey(
            'fk-acao_avaliacao_recomendacao-usuario_id',
            'acao_avaliacao_recomendacao',
            'usuario_id',
            'usuario',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-acao_avaliacao_recomendacao-usuario_resposta_id',
            'acao_avaliacao_recomendacao',
            'usuario_resposta_id'
        );

        $this->addForeignKey(
            'fk-acao_avaliacao_recomendacao-usuario_resposta_id',
            'acao_avaliacao_recomendacao',
            'usuario_resposta_id',
            'usuario',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-acao_avaliacao_recomendacao-usuario_resposta_id', 'acao_avaliacao_recomendacao');

        $this->dropIndex('idx-acao_avaliacao_recomendacao-usuario_resposta_id', 'acao_avaliacao_recomendacao');

        $this->dropForeignKey('fk-acao_avaliacao_recomendacao-usuario_id', 'acao_avaliacao_recomendacao');

        $this->dropIndex('idx-acao_avaliacao_recomendacao-usuario_id', 'acao_avaliacao_recomendacao');

        $this->dropForeignKey('fk-acao_avaliacao_recomendacao-acao_id', 'acao_avaliacao_recomendacao');

        $this->dropIndex('idx-acao_avaliacao_recomendacao-acao_id', 'acao_avaliacao_recomendacao');

        $this->dropTable('{{%acao_avaliacao_recomendacao}}');
    }
}
