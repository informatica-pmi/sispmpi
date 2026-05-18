<?php

namespace app\modules\avaliar\controllers;

use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\PlanoIntegridade;
use app\models\PlanoIntegridadeNovo;
use app\models\User;
use app\models\Status;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\DiagnosticoInfoEstrategica;
use app\modules\elaborar\models\DiagnosticoResultado;
use app\modules\elaborar\models\Eixo;
use app\modules\elaborar\models\Grupo;
use app\modules\elaborar\models\GrupoInstituido;
use app\modules\elaborar\models\GrupoServidor;
use app\modules\elaborar\models\Subeixo;
use app\modules\elaborar\models\Validacao;

/**
 * Recomendacao controller for the `avalilar` module
 */
class PlanoIntegridadeNovoController extends Controller
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
     * Undocumented function
     *
     * @param [type] $filter
     * @return void
     */
    public function actionUpdate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $userId = User::getIdentidade('id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelPlanoIntegridadeNovo = $modelPlano->getPlanoIntegridadeNovos()
            ->where(['autorizado' => null])
            ->one();

        $modelPlanoIntegridadeNovo->scenario = PlanoIntegridadeNovo::SCENARIO_UPDATE;

        $isNewVersion = $modelPlanoIntegridadeNovo->tipo === PlanoIntegridadeNovo::TIPO_NOVA_VERSAO;

        if ($modelPlanoIntegridadeNovo->load(Yii::$app->request->post())) {
            $modelPlanoIntegridadeNovo->usuario_autorizador_id = $userId;

            $transaction = Yii::$app->db->beginTransaction();

            $wasAuthorized = $modelPlanoIntegridadeNovo->autorizado == Status::STATUS_SIM;

            try {
                if ($flag = $modelPlanoIntegridadeNovo->save()) {
                    if ($wasAuthorized) {
                        $modelPlano->status = Status::PLANO_OBSOLETO;

                        if ($flag = $modelPlano->save()) {
                            $newPlano = new PlanoIntegridade();
                            $newPlano->orgao_id = $userOrgaoId;

                            if ($isNewVersion) {
                                $newPlano->edicao = $modelPlano->edicao;
                                $newPlano->versao = $modelPlano->versao + 0.01;
                                $newPlano->status = Status::PLANO_ELABORACAO;
                                $newPlano->plano_integridade_referencia_id = $modelPlano->id;

                                if ($flag = $newPlano->save()) {
                                    // grupo
                                    $oldGrupo = $modelPlano->grupo;
                                    $newGrupo = new Grupo();
                                    $newGrupo->attributes = $oldGrupo->attributes;
                                    $newGrupo->plano_integridade_id = $newPlano->id;

                                    if ($flag = $newGrupo->save()) {
                                        foreach ($oldGrupo->grupoInstituidos as $grupoInstituido) {
                                            $newGrupoInstituido = new GrupoInstituido();
                                            $newGrupoInstituido->attributes = $grupoInstituido->attributes;

                                            $newGrupoInstituido->grupo_id = $newGrupo->id;

                                            if (! ($flag = $newGrupoInstituido->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }
                                        }

                                        foreach ($oldGrupo->grupoServidors as $grupoServidor) {
                                            $newGrupoServidor = new GrupoServidor();
                                            $newGrupoServidor->attributes = $grupoServidor->attributes;

                                            $newGrupoServidor->grupo_id = $newGrupo->id;

                                            if (! ($flag = $newGrupoServidor->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }
                                        }
                                    }

                                    // diagnostico
                                    $oldDiagnostico = $modelPlano->diagnostico;
                                    $newDiagnostico = new Diagnostico();
                                    $newDiagnostico->attributes = $oldDiagnostico->attributes;
                                    $newDiagnostico->plano_integridade_id = $newPlano->id;

                                    if ($flag = $newDiagnostico->save()) {
                                        $newDiagnosticoInfoEstrategica = new DiagnosticoInfoEstrategica();
                                        $newDiagnosticoInfoEstrategica->attributes = $oldDiagnostico
                                            ->diagnosticoInfoEstrategica
                                            ->attributes;
                                        $newDiagnosticoInfoEstrategica->diagnostico_id = $newDiagnostico->id;

                                        if (! ($flag = $newDiagnosticoInfoEstrategica->save())) {
                                            $transaction->rollBack();
                                        }

                                        $newDiagnosticoResultado = new DiagnosticoResultado();
                                        $newDiagnosticoResultado->attributes = $oldDiagnostico
                                            ->diagnosticoResultado
                                            ->attributes;
                                        $newDiagnosticoResultado->diagnostico_id = $newDiagnostico->id;

                                        if (! ($flag = $newDiagnosticoResultado->save())) {
                                            $transaction->rollBack();
                                        }

                                        $diagnosticoInstrumentoItems = ArrayHelper::getColumn(
                                            $oldDiagnostico->diagnosticoInstrumentos,
                                            function ($diagnosticoInstrumento) use ($newDiagnostico) {
                                                return [$diagnosticoInstrumento['instrumento_id'], $newDiagnostico->id];
                                            }
                                        );

                                        Yii::$app->db->createCommand()->batchInsert(
                                            'diagnostico_instrumento',
                                            ['instrumento_id', 'diagnostico_id'],
                                            $diagnosticoInstrumentoItems
                                        )->execute();

                                        $diagnosticoEixoTematicoItems = ArrayHelper::getColumn(
                                            $oldDiagnostico->diagnosticoEixoTematicos,
                                            function ($diagnosticoEixoTematico) use ($newDiagnostico) {
                                                return [$diagnosticoEixoTematico['eixo_tematico_id'], $newDiagnostico->id];
                                            }
                                        );

                                        Yii::$app->db->createCommand()->batchInsert(
                                            'diagnostico_eixo_tematico',
                                            ['eixo_tematico_id', 'diagnostico_id'],
                                            $diagnosticoEixoTematicoItems
                                        )->execute();
                                    }

                                    // redacao
                                    $eixos = $modelPlano->eixos;

                                    foreach ($eixos as $eixo) {
                                        $newEixo = new Eixo();
                                        $newEixo->attributes = $eixo->attributes;
                                        $newEixo->plano_integridade_id = $newPlano->id;

                                        if (! ($flag = $newEixo->save(false))) {
                                            $transaction->roolBack();
                                            break;
                                        }

                                        foreach ($eixo->subeixos as $subeixo) {
                                            $newSubeixo = new Subeixo();
                                            $newSubeixo->attributes = $subeixo->attributes;
                                            $newSubeixo->eixo_id = $newEixo->id;

                                            if (! ($flag = $newSubeixo->save(false))) {
                                                $transaction->roolBack();
                                                break;
                                            }

                                            foreach ($subeixo->acaos as $acao) {
                                                $newAcao = new Acao();
                                                $newAcao->attributes = $acao->attributes;
                                                $newAcao->subeixo_id = $newSubeixo->id;
                                                $newAcao->eixo_id = $newEixo->id;
                                                $newAcao->acao_referencia_id = $acao->id;
                                                $newAcao->classificacao = null;
                                                $newAcao->previsao_inicio = null;
                                                $newAcao->previsao_conclusao = null;
                                                $newAcao->orcamento_previsto = null;
                                                $newAcao->status = Status::ACAO_N_INICIALIZADA;

                                                if (! ($flag = $newAcao->save(false))) {
                                                    $transaction->roolBack();
                                                    break;
                                                }

                                                $acaoUnidadeApoioItems = ArrayHelper::getColumn(
                                                    $acao->acaoUnidadeApoios,
                                                    function ($acaoUnidadeApoio) use ($newAcao) {
                                                        return [
                                                            $newAcao->id,
                                                            $acaoUnidadeApoio['unidade_administrativa_id']
                                                        ];
                                                    }
                                                );

                                                Yii::$app->db->createCommand()->batchInsert(
                                                    'acao_unidade_apoio',
                                                    ['acao_id', 'unidade_administrativa_id'],
                                                    $acaoUnidadeApoioItems
                                                )->execute();
                                            }
                                        }

                                        $acaosWithoutSubeixo = $eixo->getAcaos()
                                            ->where(['subeixo_id' => null])
                                            ->all();

                                        foreach ($acaosWithoutSubeixo as $acao) {
                                            $newAcao = new Acao();
                                            $newAcao->attributes = $acao->attributes;
                                            $newAcao->eixo_id = $newEixo->id;
                                            $newAcao->acao_referencia_id = $acao->id;
                                            $newAcao->classificacao = null;
                                            $newAcao->previsao_inicio = null;
                                            $newAcao->previsao_conclusao = null;
                                            $newAcao->orcamento_previsto = null;
                                            $newAcao->status = Status::ACAO_N_INICIALIZADA;

                                            if (! ($flag = $newAcao->save(false))) {
                                                $transaction->roolBack();
                                                break;
                                            }

                                            $acaoUnidadeApoioItems = ArrayHelper::getColumn(
                                                $acao->acaoUnidadeApoios,
                                                function ($acaoUnidadeApoio) use ($newAcao) {
                                                    return [
                                                        $newAcao->id,
                                                        $acaoUnidadeApoio['unidade_administrativa_id']
                                                    ];
                                                }
                                            );

                                            Yii::$app->db->createCommand()->batchInsert(
                                                'acao_unidade_apoio',
                                                ['acao_id', 'unidade_administrativa_id'],
                                                $acaoUnidadeApoioItems
                                            )->execute();
                                        }
                                    }

                                    // validacao
                                    $newValidacao = new Validacao();
                                    $oldValidacao = $modelPlano->validacao;

                                    if (!empty($oldValidacao)) {
                                        $newValidacao->attributes = $modelPlano->validacao->attributes;
                                        $newValidacao->plano_integridade_id = $newPlano->id;

                                        if ($flag = $newValidacao->save(false)) {
                                            $validacaoStakeholderItems = ArrayHelper::getColumn(
                                                $modelPlano->validacao->validacaoStakeholders,
                                                function ($validacaoStakeholder) use ($newValidacao) {
                                                    return [$newValidacao->id, $validacaoStakeholder['stakeholder_id']];
                                                }
                                            );

                                            Yii::$app->db->createCommand()->batchInsert(
                                                'validacao_stakeholder',
                                                ['validacao_id', 'stakeholder_id'],
                                                $validacaoStakeholderItems
                                            )->execute();
                                        }
                                    }
                                }
                            } else {
                                $numEdicao = (int) $modelPlano->edicao;
                                $numEdicao += 1;
                                $newPlano->edicao = "{$numEdicao}° Edição";
                                $newPlano->versao = 1;
                                $newPlano->status = Status::PLANO_N_INICIADO;
                                $flag = $newPlano->save();
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();

                        $usersAltaMonitoramento = User::find()
                            ->joinWith(['authAssignment'])
                            ->where(['orgao_id' => $userOrgaoId, 'status' => Status::STATUS_ATIVO])
                            ->andWhere([
                                'in',
                                'item_name',
                                [User::PERFIL_ALTA_ADMINISTRACAO, User::PERFIL_MONITORAMENTO]
                            ])
                            ->all();

                        if ($usersAltaMonitoramento) {
                            Mail::sendMultiple(
                                $wasAuthorized ?
                                    './avaliar/alta-monitoramento-plano-integridade-novo' :
                                    './avaliar/alta-monitoramento-plano-integridade-novo-negado',
                                [
                                    'tipoSolicitacao' => PlanoIntegridadeNovo::getTipo($modelPlanoIntegridadeNovo->tipo),
                                    'justificativa' => $modelPlanoIntegridadeNovo->justificativa
                                ],
                                $usersAltaMonitoramento,
                                'Atualização do plano de integridade',
                            );
                        }

                        Universal::flash();
                        return $this->redirect(['/site/index']);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                echo '<pre>';
                var_dump($e->getLine());
            }
        }

        return $this->render('update', [
            'modelPlanoIntegridadeNovo' => $modelPlanoIntegridadeNovo,
            'isNewVersion' => $isNewVersion,
        ]);
    }
}
