<?php

use yii\db\Migration;

/**
 * Class m210527_165251_alter_servidor_table
 */
class m210527_165251_alter_servidor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('servidor', 'masp_matricula', $this->string(255)->null());

        $this->alterColumn('servidor', 'unidade_administrativa_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('servidor', 'masp_matricula', $this->string(255)->notNull());

        $this->alterColumn('servidor', 'unidade_administrativa_id', $this->integer()->notNull());
    }
}
