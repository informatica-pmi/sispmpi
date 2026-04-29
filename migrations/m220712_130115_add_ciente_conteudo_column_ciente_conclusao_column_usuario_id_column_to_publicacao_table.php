<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%publicacao}}`.
 */
class m220712_130115_add_ciente_conteudo_column_ciente_conclusao_column_usuario_id_column_to_publicacao_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('publicacao', 'ciente_conteudo', $this->boolean()->notNull()->after('plano_integridade_arquivo'));

        $this->addColumn('publicacao', 'ciente_conclusao', $this->boolean()->notNull()->after('ciente_conteudo'));

        $this->addColumn('publicacao', 'usuario_id', $this->integer()->null()->after('ciente_conclusao'));

        $this->createIndex(
            'idx-publicacao-usuario_id',
            'publicacao',
            'usuario_id'
        );

        $this->addForeignKey(
            'fk-publicacao-usuario_id',
            'publicacao',
            'usuario_id',
            'usuario',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-publicacao-usuario_id',
            'publicacao'
        );

        $this->dropIndex(
            'idx-publicacao-usuario_id',
            'publicacao'
        );

        $this->dropColumn('publicacao', 'usuario_id');

        $this->dropColumn('publicacao', 'ciente_conclusao');

        $this->dropColumn('publicacao', 'ciente_conteudo');
    }
}
