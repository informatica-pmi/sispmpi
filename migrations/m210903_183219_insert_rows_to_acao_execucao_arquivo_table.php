<?php

use app\modules\executar\models\AcaoExecucao;
use yii\db\Migration;

/**
 * Class m210903_183219_insert_rows_to_acao_execucao_arquivo_table
 */
class m210903_183219_insert_rows_to_acao_execucao_arquivo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $acaoExecucaos = AcaoExecucao::find()->where(['not', ['evidencia_arquivo' => null]])->all();

        $finalArray = [];
        foreach ($acaoExecucaos as $acaoExecucao) {
            $arr = [$acaoExecucao->id, $acaoExecucao->evidencia_arquivo];

            array_push($finalArray, $arr);
        }

        $this->batchInsert('acao_execucao_arquivo', ['acao_execucao_id', 'arquivo_id'], $finalArray);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('acao_execucao_arquivo');
    }
}
