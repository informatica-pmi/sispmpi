<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%historico}}`.
 */
class m210622_170837_add_usuario_perfil_column_to_historico_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('historico', 'usuario_perfil', $this->string(255)->null()->after('usuario_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('historico', 'usuario_perfil');
    }
}
