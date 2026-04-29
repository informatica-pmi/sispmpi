<?php

use yii\db\Migration;

/**
 * Class m220106_170446_alter_acao_desenvolvida_column_from_promover_integridade_table
 */
class m220106_170446_alter_acao_desenvolvida_column_from_promover_integridade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('promover_integridade', 'acao_desenvolvida', $this->text()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('promover_integridade', 'acao_desenvolvida', $this->string(255)->notNull());
    }
}
