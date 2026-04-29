<?php

use yii\db\Migration;

/**
 * Class m220609_115356_alter_columns_from_publicacao_table
 */
class m220609_115356_alter_columns_from_publicacao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('publicacao', 'plano_comunicacao', $this->tinyInteger()->null());
        $this->alterColumn('publicacao', 'plano_treinamento', $this->tinyInteger()->null());
        $this->alterColumn('publicacao', 'plano_acao_arquivo', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('publicacao', 'plano_acao_arquivo', $this->integer()->notNull());
        $this->alterColumn('publicacao', 'plano_treinamento', $this->tinyInteger()->notNull());
        $this->alterColumn('publicacao', 'plano_comunicacao', $this->tinyInteger()->notNull());
    }
}
