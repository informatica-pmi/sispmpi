<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%diagnostico_resultado}}`.
 */
class m220614_182144_add_columns_to_diagnostico_resultado_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'diagnostico_resultado',
            'estrutura_governanca',
            $this->text()->null()->after('objetivos_estrategicos')
        );

        $this->addColumn(
            'diagnostico_resultado',
            'periodicidade_monitoramentos',
            $this->string(255)->null()->after('estrutura_governanca')
        );

        $this->addColumn(
            'diagnostico_resultado',
            'periodicidade_avaliacoes',
            $this->string(255)->null()->after('periodicidade_monitoramentos')
        );

        $this->addColumn(
            'diagnostico_resultado',
            'periodicidade_atualizacoes',
            $this->string(255)->null()->after('periodicidade_avaliacoes')
        );

        $this->addColumn(
            'diagnostico_resultado',
            'aspectos_comunicacao',
            $this->text()->null()->after('periodicidade_atualizacoes')
        );

        $this->addColumn(
            'diagnostico_resultado',
            'aspectos_capacitacao',
            $this->text()->null()->after('aspectos_comunicacao')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('diagnostico_resultado', 'aspectos_capacitacao');
        $this->dropColumn('diagnostico_resultado', 'aspectos_comunicacao');
        $this->dropColumn('diagnostico_resultado', 'periodicidade_atualizacoes');
        $this->dropColumn('diagnostico_resultado', 'periodicidade_avaliacoes');
        $this->dropColumn('diagnostico_resultado', 'periodicidade_monitoramentos');
        $this->dropColumn('diagnostico_resultado', 'estrutura_governanca');
    }
}
