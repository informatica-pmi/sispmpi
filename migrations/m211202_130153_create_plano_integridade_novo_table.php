<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plano_integridade_novo}}`.
 */
class m211202_130153_create_plano_integridade_novo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plano_integridade_novo}}', [
            'id' => $this->primaryKey(),
            'plano_integridade_id' => $this->integer()->notNull(),
            'tipo' => $this->tinyInteger()->notNull(),
            'usuario_solicitante_id' => $this->integer()->notNull(),
            'autorizado' => $this->tinyInteger()->null(),
            'usuario_autorizador_id' => $this->integer()->null(),
            'justificativa' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-plano_integridade_novo-plano_integridade_id',
            'plano_integridade_novo',
            'plano_integridade_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_novo-plano_integridade_id',
            'plano_integridade_novo',
            'plano_integridade_id',
            'plano_integridade',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-plano_integridade_novo-usuario_solicitante_id',
            'plano_integridade_novo',
            'usuario_solicitante_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_novo-usuario_solicitante_id',
            'plano_integridade_novo',
            'usuario_solicitante_id',
            'usuario',
            'id',
            'NO ACTION'
        );

        $this->createIndex(
            'idx-plano_integridade_novo-usuario_autorizador_id',
            'plano_integridade_novo',
            'usuario_autorizador_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade_novo-usuario_autorizador_id',
            'plano_integridade_novo',
            'usuario_autorizador_id',
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
        $this->dropForeignKey('fk-plano_integridade_novo-usuario_autorizador_id', 'plano_integridade_novo');

        $this->dropIndex('idx-plano_integridade_novo-usuario_autorizador_id', 'plano_integridade_novo');

        $this->dropForeignKey('fk-plano_integridade_novo-usuario_solicitante_id', 'plano_integridade_novo');

        $this->dropIndex('idx-plano_integridade_novo-usuario_solicitante_id', 'plano_integridade_novo');

        $this->dropForeignKey('fk-plano_integridade_novo-plano_integridade_id', 'plano_integridade_novo');

        $this->dropIndex('idx-plano_integridade_novo-plano_integridade_id', 'plano_integridade_novo');

        $this->dropTable('{{%plano_integridade_novo}}');
    }
}
