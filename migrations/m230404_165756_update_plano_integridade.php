<?php

use app\models\PlanoIntegridade;
use app\models\Status;
use yii\db\Migration;

/**
 * Class m230404_165756_update_plano_integridade
 */
class m230404_165756_update_plano_integridade extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $planos = PlanoIntegridade::find()
            ->joinWith(['orgao'])
            ->where([
                'plano_integridade.status' => Status::PLANO_N_INICIADO,
                'orgao.status' => Status::STATUS_ATIVO
            ])
            ->all();

        foreach ($planos as $plano) {
            $grupo = $plano->grupo;
            if (!empty($plano->diagnostico)) {
                $diagnosticoInfoEstrategica = $plano->diagnostico->diagnosticoInfoEstrategica;
            }
            $eixos = $plano->eixos;
            $validacao = $plano->validacao;
            if (!empty($grupo) || !empty($eixos) || !empty($validacao) || (isset($diagnosticoInfoEstrategica) && !empty($diagnosticoInfoEstrategica))) {
                $plano->status = Status::PLANO_ELABORACAO;
                $plano->save();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $planos = PlanoIntegridade::find()
            ->joinWith(['orgao'])
            ->where([
                'plano_integridade.status' => Status::PLANO_ELABORACAO,
                'orgao.status' => Status::STATUS_ATIVO
            ])
            ->all();

        foreach ($planos as $plano) {
            $grupo = $plano->grupo;

            if (empty($grupo)) {
                $plano->status = Status::PLANO_N_INICIADO;
                $plano->save();
            }
        }
    }
}
