<?php

use app\models\AuthItem;
use yii\db\Migration;

/**
 * Class m230524_122853_update_permissions
 */
class m230524_122853_update_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name' => 'indicador-index',
            'type' => 2,
            'description' => 'Exibir indicadores',
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert('auth_item_child', [
            'parent' => 'Administrador',
            'child' => 'indicador-index'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('auth_item_child', [
            'parent' => 'Administrador',
            'child' => 'indicador-index'
        ]);

        $this->delete('auth_item', [
            'name' => 'indicador-index'
        ]);
    }
}
