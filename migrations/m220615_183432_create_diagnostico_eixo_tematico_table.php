<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%diagnostico_eixo_tematico}}`.
 */
class m220615_183432_create_diagnostico_eixo_tematico_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%diagnostico_eixo_tematico}}', [
            'id' => $this->primaryKey(),
            'eixo_tematico_id' => $this->integer()->notNull(),
            'diagnostico_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-diagnostico_eixo_tematico-eixo_tematico_id',
            'diagnostico_eixo_tematico',
            'eixo_tematico_id'
        );

        $this->addForeignKey(
            'fk-diagnostico_eixo_tematico-eixo_tematico_id',
            'diagnostico_eixo_tematico',
            'eixo_tematico_id',
            'eixo_tematico',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-diagnostico_eixo_tematico-diagnostico_id',
            'diagnostico_eixo_tematico',
            'diagnostico_id'
        );

        $this->addForeignKey(
            'fk-diagnostico_eixo_tematico-diagnostico_id',
            'diagnostico_eixo_tematico',
            'diagnostico_id',
            'diagnostico',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-diagnostico_eixo_tematico-diagnostico_id', 'diagnostico_eixo_tematico');

        $this->dropIndex('idx-diagnostico_eixo_tematico-diagnostico_id', 'diagnostico_eixo_tematico');

        $this->dropForeignKey('fk-diagnostico_eixo_tematico-eixo_tematico_id', 'diagnostico_eixo_tematico');

        $this->dropIndex('idx-diagnostico_eixo_tematico-eixo_tematico_id', 'diagnostico_eixo_tematico');

        $this->dropTable('{{%diagnostico_eixo_tematico}}');
    }
}
