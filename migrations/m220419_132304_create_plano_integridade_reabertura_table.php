<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plano_integridade_reabertura}}`.
 */
class m220419_132304_create_plano_integridade_reabertura_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plano_integridade_reabertura}}', [
            'id' => $this->primaryKey(),
            'plano_integridade_id' => $this->integer()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-plano_integridade_reabertura-plano_integridade_id',
            'plano_integridade_reabertura',
            'plano_integridade_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_reabertura-plano_integridade_id',
            'plano_integridade_reabertura',
            'plano_integridade_id',
            'plano_integridade',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-plano_integridade_reabertura-usuario_id',
            'plano_integridade_reabertura',
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_reabertura-usuario_id',
            'plano_integridade_reabertura',
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
        $this->dropForeignKey(
            'fk-plano_integridade_reabertura-usuario_id',
            'plano_integridade_reabertura'
        );

        $this->dropIndex(
            'idx-plano_integridade_reabertura-usuario_id',
            'plano_integridade_reabertura'
        );

        $this->dropForeignKey(
            'fk-plano_integridade_reabertura-plano_integridade_id',
            'plano_integridade_reabertura'
        );

        $this->dropIndex(
            'idx-plano_integridade_reabertura-plano_integridade_id',
            'plano_integridade_reabertura'
        );

        $this->dropTable('{{%plano_integridade_reabertura}}');
    }
}
