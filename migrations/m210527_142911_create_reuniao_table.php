<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reuniao}}`.
 */
class m210527_142911_create_reuniao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reuniao}}', [
            'id' => $this->primaryKey(),
            'plano_integridade_id' => $this->integer()->notNull(),
            'data' => $this->date()->notNull(),
            'nome' => $this->string(255)->notNull(),
            'pauta' => $this->text()->notNull(),
            'registro' => $this->text()->notNull(),
            'usuario_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex(
            'idx-reuniao-plano_integridade_id', 
            'reuniao', 
            'plano_integridade_id'
        );

        $this->addForeignKey(
            'fk-reuniao-plano_integridade_id', 
            'reuniao', 
            'plano_integridade_id', 
            'plano_integridade', 
            'id', 
            'CASCADE'
        );

        $this->createIndex(
            'idx-reuniao-usuario_id', 
            'reuniao', 
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-reuniao-usuario_id', 
            'reuniao', 
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
        $this->dropForeignKey('fk-reuniao-usuario_id', 'reuniao');

        $this->dropIndex('idx-reuniao-usuario_id', 'reuniao');

        $this->dropForeignKey('fk-reuniao-plano_integridade_id', 'reuniao');

        $this->dropIndex('idx-reuniao-plano_integridade_id', 'reuniao');

        $this->dropTable('{{%reuniao}}');
    }
}
