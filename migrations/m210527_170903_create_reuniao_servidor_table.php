<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reuniao_servidor}}`.
 */
class m210527_170903_create_reuniao_servidor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reuniao_servidor}}', [
            'id' => $this->primaryKey(),
            'reuniao_id' => $this->integer()->notNull(),
            'servidor_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-reuniao_servidor-reuniao_id', 
            'reuniao_servidor', 
            'reuniao_id'
        );

        $this->addForeignKey(
            'fk-reuniao_servidor-reuniao_id', 
            'reuniao_servidor', 
            'reuniao_id', 
            'reuniao', 
            'id', 
            'CASCADE'
        );

        $this->createIndex(
            'idx-reuniao_servidor-servidor_id', 
            'reuniao_servidor', 
            'servidor_id'
        );

        $this->addForeignKey(
            'fk-reuniao_servidor-servidor_id', 
            'reuniao_servidor', 
            'servidor_id', 
            'servidor', 
            'id', 
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-reuniao_servidor-servidor_id', 'reuniao_servidor');

        $this->dropIndex('idx-reuniao_servidor-servidor_id', 'reuniao_servidor');

        $this->dropForeignKey('fk-reuniao_servidor-reuniao_id', 'reuniao_servidor');

        $this->dropIndex('idx-reuniao_servidor-reuniao_id', 'reuniao_servidor');

        $this->dropTable('{{%reuniao_servidor}}');
    }
}
