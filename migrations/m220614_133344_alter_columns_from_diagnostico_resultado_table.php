<?php

use yii\db\Migration;

/**
 * Class m220614_133344_alter_columns_from_diagnostico_resultado_table
 */
class m220614_133344_alter_columns_from_diagnostico_resultado_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('diagnostico_resultado', 'objetivos_trabalhados', $this->text()->null());
        $this->alterColumn('diagnostico_resultado', 'objetivos_estrategicos', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('diagnostico_resultado', 'objetivos_trabalhados', $this->text()->notNull());
        $this->alterColumn('diagnostico_resultado', 'objetivos_estrategicos', $this->text()->notNull());
    }
}
