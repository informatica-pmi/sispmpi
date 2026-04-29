<?php

namespace app\components\helpers;

use yii\helpers\StringHelper;
use app\models\Acao;
use app\models\Status;
use app\components\helpers\Universal;
use app\models\Historico;
use app\modules\executar\models\AcaoServidor;

class Semaphore
{
    /**
     * @param object $model instace of class for search register specific of organ
     * @return
     */
    public static function generate(Acao $acao)
    {
        $execucao = $acao->acaoExecucao;
        $bgColorDot = '';
        $today = date('Y-m-d');

        if ($acao->classificacao === Acao::CLASSIFICACAO_ACAO_PONTUAL) {
            if (!empty($execucao->data_inicio)) {
                $intervalDates = Universal::intervalDates($today, $acao->previsao_conclusao);
            }

            switch ($acao->status) {
                case Status::ACAO_N_INICIALIZADA:
                    $bgColorDot = 'bg-white';
                    break;
                case Status::ACAO_EM_ANDAMENTO:
                    if ($intervalDates >= +30) {
                        $bgColorDot = 'bg-success';
                    } elseif ($intervalDates < +30 && $intervalDates >= +0) {
                        $bgColorDot = 'bg-warning';
                    } elseif ($intervalDates < -0) {
                        $bgColorDot = 'bg-danger';
                    }
                    break;
                case Status::ACAO_CONCLUIDA:
                    $bgColorDot = 'bg-primary';
                    break;
                case Status::ACAO_DESCONTINUADA:
                    $bgColorDot = 'bg-secondary';
                    break;
            }
        } elseif ($acao->classificacao === Acao::CLASSIFICACAO_ACAO_CONTINUA) {
            $queryLastRegisterHistoric = Historico::find()
                ->where([
                    'model' => StringHelper::basename(get_class($acao)),
                    'id_registro' => $acao->id,
                ])
                ->orWhere([
                    'model' => StringHelper::basename(get_class(new AcaoServidor())),
                    'campo' => 'acao_id',
                    'antigo_valor' => $acao->id,
                ])
                ->orWhere([
                    'model' => StringHelper::basename(get_class(new AcaoServidor())),
                    'campo' => 'acao_id',
                    'novo_valor' => $acao->id,
                ]);

            if (!is_null($execucao)) {
                $queryLastRegisterHistoric->orWhere([
                    'model' => StringHelper::basename(get_class($execucao)),
                    'id_registro' => $execucao->id,
                ]);
            }

            $lastRegisterHistoric = $queryLastRegisterHistoric->orderBy(['id' => SORT_DESC])
                ->one();

            $dateForInterval = $lastRegisterHistoric ?
                $lastRegisterHistoric->created_at :
                $acao->eixo->planoIntegridade->publicacao->created_at;

            $intervalDates = Universal::intervalDates($dateForInterval, $today);

            switch ($acao->status) {
                case Status::ACAO_N_INICIALIZADA:
                    $bgColorDot = 'bg-white';
                    break;
                case Status::ACAO_EM_ANDAMENTO:
                    if ($intervalDates < +30 && $intervalDates >= +0) {
                        $bgColorDot = 'bg-success';
                    } elseif ($intervalDates >= +30 && $intervalDates < +60) {
                        $bgColorDot = 'bg-warning';
                    } elseif ($intervalDates >= +60) {
                        $bgColorDot = 'bg-danger';
                    }
                    break;
                case Status::ACAO_CONCLUIDA:
                    $bgColorDot = 'bg-primary';
                    break;
                case Status::ACAO_DESCONTINUADA:
                    $bgColorDot = 'bg-secondary';
                    break;
            }
        }

        return $bgColorDot;
    }
}
