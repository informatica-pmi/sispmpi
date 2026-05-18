<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%grupo_instituido}}`.
 */
class m220803_165933_add_tipo_column_to_grupo_instituido_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('grupo_instituido', 'tipo', $this->tinyInteger()->notNull()->after('order'));

        $this->update('grupo_instituido', ['tipo' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('grupo_instituido', 'tipo');
    }
}
