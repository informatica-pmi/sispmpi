<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%promocao_integridade}}`.
 */
class m211130_125631_create_promocao_integridade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%promover_integridade}}', [
            'id' => $this->primaryKey(),
            'data' => $this->date()->notNull(),
            'acao_desenvolvida' => $this->string()->notNull(),
            'horas_trabalho' => $this->smallInteger()->notNull(),
            'plano_integridade_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-promover_integridade-plano_integridade_id',
            'promover_integridade',
            'plano_integridade_id'
        );

        $this->addForeignKey(
            'fk-promover_integridade-plano_integridade_id',
            'promover_integridade',
            'plano_integridade_id',
            'plano_integridade',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-promover_integridade-plano_integridade_id', 'promocao_integridade');

        $this->dropIndex('idx-promover_integridade-plano_integridade_id', 'promocao_integridade');

        $this->dropTable('{{%promover_integridade}}');
    }
}
