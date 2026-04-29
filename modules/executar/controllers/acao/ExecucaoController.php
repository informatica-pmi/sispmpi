<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\components\helpers\Universal;
use app\models\Acao;
use app\modules\executar\models\AcaoExecucao;
use app\modules\executar\models\AcaoExecucaoFatorLimitante;
use app\models\Arquivo;
use app\models\Status;
use app\modules\executar\models\AcaoExecucaoArquivo;

/**
 * ExecucaoController implements the CRUD actions for Eixo model.
 */
class ExecucaoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Atualiza uma Acao existente
     * Se a atualizacao for um sucesso, o browser te redirecionará para a página 'acao/view'
     *
     * @param int $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionCreate($acaoId)
    {
        $modelAcao = Acao::findOne($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelAcaoExecucao = empty($modelAcao->acaoExecucao) ? new AcaoExecucao() : $modelAcao->acaoExecucao;

        $planoId = $modelPlano->id;

        if ($modelAcaoExecucao->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $modelIsEmpty = empty(array_filter($modelAcaoExecucao->attributes)) &&
                    empty($modelAcaoExecucao->fatoresLimitantesIds);

                if ($modelIsEmpty) {
                    Universal::flash('error', 'Para salvar a execução é necessário preencher ao menos um campo.');
                    throw new Exception();
                }

                $modelAcaoExecucao->acao_id = $modelAcao->id;

                $modelAcaoExecucao->saveAudit = Status::STATUS_SIM;

                $modelAcaoExecucao->evidenciaFiles = UploadedFile::getInstances($modelAcaoExecucao, 'evidenciaFiles');

                $countAcaoExecucaoEvidenciaFilesExists = count($modelAcaoExecucao->acaoExecucaoArquivos);

                $countEvidenciaFiles = count($modelAcaoExecucao->evidenciaFiles);

                $totalAcaoExecucaoEvidenciaFiles = $countEvidenciaFiles + $countAcaoExecucaoEvidenciaFilesExists;

                if ($totalAcaoExecucaoEvidenciaFiles > 2) {
                    Universal::flash('error', 'São permitidos no maxímo 02 arquivos como evidência.');
                    throw new Exception();
                }

                if ($flag = $modelAcaoExecucao->save()) {
                    if ($modelAcaoExecucao->evidenciaFiles) {
                        $modelArquivo = new Arquivo();
                        $modelArquivo->files = $modelAcaoExecucao->evidenciaFiles;

                        $evidenciaFilesIds = $modelArquivo->uploads(
                            'uploads/plano_integridade',
                            $planoId,
                            "acao/{$modelAcao->id}/execucao"
                        );

                        foreach ($evidenciaFilesIds as $evidenciaFilesId) {
                            $modelAcaoExecucaoArquivo = new AcaoExecucaoArquivo();

                            $modelAcaoExecucaoArquivo->acao_execucao_id = $modelAcaoExecucao->id;
                            $modelAcaoExecucaoArquivo->arquivo_id = $evidenciaFilesId;

                            if (! ($flag = $modelAcaoExecucaoArquivo->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    $oldAcaoFatoresLimitantes = ArrayHelper::getColumn(
                        AcaoExecucaoFatorLimitante::findAll(['acao_execucao_id' => $modelAcaoExecucao->id]),
                        'id'
                    );

                    if (!empty($oldAcaoFatoresLimitantes)) {
                        AcaoExecucaoFatorLimitante::deleteAll(['id' => $oldAcaoFatoresLimitantes]);
                    }

                    if (!empty($modelAcaoExecucao->fatoresLimitantesIds)) {
                        foreach ($modelAcaoExecucao->fatoresLimitantesIds as $fatorLimitante) {
                            $newAcaoFatorlimitante = new AcaoExecucaoFatorLimitante();
                            $newAcaoFatorlimitante->fator_limitante_id = $fatorLimitante;
                            $newAcaoFatorlimitante->acao_execucao_id = $modelAcaoExecucao->id;

                            if (! ($flag = $newAcaoFatorlimitante->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    switch ($modelAcaoExecucao) {
                        case (!empty($modelAcaoExecucao->data_inicio)) && (!empty($modelAcaoExecucao->data_conclusao)):
                            $modelAcao->status = Status::ACAO_CONCLUIDA;
                            break;
                        case (!empty($modelAcaoExecucao->data_conclusao)):
                            $modelAcao->status = Status::ACAO_DESCONTINUADA;
                            break;
                        case !empty($modelAcaoExecucao->data_inicio):
                            $modelAcao->status = Status::ACAO_EM_ANDAMENTO;
                            break;
                        default:
                            $modelAcao->status = Status::ACAO_N_INICIALIZADA;
                    }

                    if (! ($flag = $modelAcao->save())) {
                        $transaction->rollBack();
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['@executar/acao/default/view', 'acaoId' => $modelAcao->id]);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
