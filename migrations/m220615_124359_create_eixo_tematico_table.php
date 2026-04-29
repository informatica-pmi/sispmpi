<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%eixo_tematico}}`.
 */
class m220615_124359_create_eixo_tematico_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%eixo_tematico}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(255)->notNull(),
            'orgao_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-eixo_tematico-orgao_id',
            'eixo_tematico',
            'orgao_id',
        );

        $this->addForeignKey(
            'fk-eixo_tematico-orgao_id',
            'eixo_tematico',
            'orgao_id',
            'orgao',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-eixo_tematico-orgao_id', 'eixo_tematico');

        $this->dropIndex('idx-eixo_tematico-orgao_id', 'eixo_tematico');

        $this->dropTable('{{%eixo_tematico}}');
    }
}
