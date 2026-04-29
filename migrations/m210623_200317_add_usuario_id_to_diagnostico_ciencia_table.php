<?php

use yii\db\Migration;

/**
 * Class m210623_200317_add_usuario_id_to_diagnostico_ciencia_table
 */
class m210623_200317_add_usuario_id_to_diagnostico_ciencia_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('diagnostico_ciencia', 'usuario_id', $this->integer()->null()->after('validado'));

        $this->createIndex(
            'idx-diagnostico_ciencia-usuario_id', 
            'diagnostico_ciencia', 
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-diagnostico_ciencia-usuario_id', 
            'diagnostico_ciencia', 
            'usuario_id', 
            'usuario', 
            'id', 
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-diagnostico_ciencia-usuario_id', 'diagnostico_ciencia');

        $this->dropIndex('idx-diagnostico_ciencia-usuario_id', 'diagnostico_ciencia');

        $this->dropColumn('diagnostico_ciencia', 'usuario_id');
    }
}
