<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plano_integridade_recomendacao}}`.
 */
class m210909_171140_create_plano_integridade_recomendacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plano_integridade_recomendacao}}', [
            'id' => $this->primaryKey(),
            'recomendacao' => $this->text()->notNull(),
            'resposta' => $this->text()->null(),
            'plano_integridade_id' => $this->integer()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'usuario_resposta_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-plano_integridade_recomendacao-plano_integridade_id',
            'plano_integridade_recomendacao',
            'plano_integridade_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_recomendacao-plano_integridade_id',
            'plano_integridade_recomendacao',
            'plano_integridade_id',
            'plano_integridade',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-plano_integridade_recomendacao-usuario_id',
            'plano_integridade_recomendacao',
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_recomendacao-usuario_id',
            'plano_integridade_recomendacao',
            'usuario_id',
            'usuario',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-plano_integridade_recomendacao-usuario_resposta_id',
            'plano_integridade_recomendacao',
            'usuario_resposta_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_recomendacao-usuario_resposta_id',
            'plano_integridade_recomendacao',
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
        $this->dropForeignKey('fk-plano_integridade_recomendacao-usuario_resposta_id', 'plano_integridade_recomendacao');

        $this->dropIndex('idx-plano_integridade_recomendacao-usuario_resposta_id', 'plano_integridade_recomendacao');

        $this->dropForeignKey('fk-plano_integridade_recomendacao-usuario_id', 'plano_integridade_recomendacao');

        $this->dropIndex('idx-plano_integridade_recomendacao-usuario_id', 'plano_integridade_recomendacao');

        $this->dropForeignKey('fk-plano_integridade_recomendacao-plano_integridade_id', 'plano_integridade_recomendacao');

        $this->dropIndex('idx-plano_integridade_recomendacao-plano_integridade_id', 'plano_integridade_recomendacao');

        $this->dropTable('{{%plano_integridade_recomendacao}}');
    }
}
