<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%plano_integridade}}`.
 */
class m211202_132807_add_versao_column_plano_integridade_referencia_id_column_to_plano_integridade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('plano_integridade', 'versao', $this->float()->notNull()->defaultValue(1)->after('edicao'));

        $this->addColumn(
            'plano_integridade',
            'plano_integridade_referencia_id',
            $this->integer()->null()->after('orgao_id')
        );

        $this->createIndex(
            'idx-plano_integridade-plano_integridade_referencia_id',
            'plano_integridade',
            'plano_integridade_referencia_id'
        );

        $this->addForeignKey(
            'fk-plano_integridade-plano_integridade_referencia_id',
            'plano_integridade',
            'plano_integridade_referencia_id',
            'plano_integridade',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-plano_integridade-plano_integridade_referencia_id', 'plano_integridade');

        $this->dropIndex('idx-plano_integridade-plano_integridade_referencia_id', 'plano_integridade');

        $this->dropColumn('plano_integridade', 'plano_integridade_referencia_id');

        $this->dropColumn('plano_integridade', 'versao');
    }
}
