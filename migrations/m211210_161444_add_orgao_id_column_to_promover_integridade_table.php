<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%promover_integridade}}`.
 */
class m211210_161444_add_orgao_id_column_to_promover_integridade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'promover_integridade',
            'orgao_id',
            $this->integer()->notNull()->after('plano_integridade_id')
        );

        $this->createIndex(
            'idx-promover_integridade-orgao_id',
            'promover_integridade',
            'orgao_id'
        );

        $this->addForeignKey(
            'fk-promover_integridade-orgao_id',
            'promover_integridade',
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
        $this->dropForeignKey('fk-promover_integridade-orgao_id', 'promover_integridade');

        $this->dropIndex('idx-promover_integridade-orgao_id', 'promover_integridade');

        $this->dropColumn('promover_integridade', 'orgao_id');
    }
}
