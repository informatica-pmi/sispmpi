<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%acao}}`.
 */
class m211207_122352_add_acao_referencia_id_column_to_acao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('acao', 'acao_referencia_id', $this->integer()->null()->after('status'));

        $this->createIndex('idx-acao-acao_referencia_id', 'acao', 'acao_referencia_id');

        $this->addForeignKey(
            'fk-acao-acao_referencia_id',
            'acao',
            'acao_referencia_id',
            'acao',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-acao-acao_referencia_id', 'acao');

        $this->dropIndex('idx-acao-acao_referencia_id', 'acao');

        $this->dropColumn('acao', 'acao_referencia_id');
    }
}
