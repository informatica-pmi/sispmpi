<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\components\helpers\Dynamic;
use app\models\Acao;
use app\modules\executar\models\AcaoServidor;
use app\models\AcaoUnidadeApoio;
use app\models\Historico;
use app\models\Servidor;
use app\models\Status;
use app\models\User;

/**
 * ResponsabilidadeController implements the CRUD actions for Acao model.
 */
class ResponsabilidadeController extends Controller
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
     * Se a atualização for um sucesso, o browser te redirecionará para a página 'acao/view'
     *
     * @param $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionUpdate($acaoId)
    {
        $modelAcao = Acao::findOne($acaoId);

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelServidor = empty($modelAcao->acaoServidorResponsavel) ?
            new Servidor() :
            $modelAcao->acaoServidorResponsavel->servidor;

        $modelServidor->scenario = Servidor::SCENARIO_EXECUTAR;

        $servidorsEnvolvido = ArrayHelper::getColumn($modelAcao->acaoServidorsEnvolvido, 'servidor');

        $modelsServidor = empty($servidorsEnvolvido) ? [new Servidor()] : $servidorsEnvolvido;

        Dynamic::scenario($modelsServidor, Servidor::SCENARIO_EXECUTAR);

        $request = Yii::$app->request;

        if ($modelAcao->load($request->post()) && $modelServidor->load($request->post())) {
            $newPost['_csrf'] = $request->post('_csrf');
            $newPost['Servidor'] = array_filter($request->post('Servidor'), function ($key) {
                return is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            $request->setBodyParams($newPost);

            $oldEnvolvidoIds = ArrayHelper::map($modelsServidor, 'id', 'id');

            $modelsServidor = Model::createMultiple(Servidor::className(), $modelsServidor);
            Dynamic::scenario($modelsServidor, Servidor::SCENARIO_EXECUTAR);
            Model::loadMultiple($modelsServidor, Yii::$app->request->post());

            $deletedEnvolvidoIds = array_diff(
                $oldEnvolvidoIds,
                array_filter(ArrayHelper::map($modelsServidor, 'id', 'id'))
            );

            $clearDeletedEnvolvidoIds = array_filter($deletedEnvolvidoIds);

            $valid = $modelAcao->validate();
            $valid = $modelServidor->validate() && $valid;
            $valid = Model::validateMultiple(
                $modelsServidor,
                [
                    'nome',
                    'masp_matricula',
                    'unidade_administrativa_id'
                ]
            ) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $modelAcao->saveAudit = Status::STATUS_SIM;

                    if ($flag = $modelAcao->save(false)) {
                        $oldUnidadeApoioIds = ArrayHelper::getColumn(
                            AcaoUnidadeApoio::findAll(['acao_id' => $modelAcao->id]),
                            'id'
                        );

                        if (!empty($oldUnidadeApoioIds)) {
                            AcaoUnidadeApoio::deleteAll(['id' => $oldUnidadeApoioIds]);
                        }

                        if (!empty($modelAcao->unidadeApoioIds)) {
                            foreach ($modelAcao->unidadeApoioIds as $unidadeApoio) {
                                $newAcaoUnidadeApoio = new AcaoUnidadeApoio();
                                $newAcaoUnidadeApoio->unidade_administrativa_id = $unidadeApoio;
                                $newAcaoUnidadeApoio->acao_id = $modelAcao->id;

                                if (! ($flag = $newAcaoUnidadeApoio->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        $servidorResponsavelIsNewRecord = $modelServidor->isNewRecord;

                        $modelServidor->saveAudit = Status::STATUS_SIM;

                        if ($flag = $modelServidor->save(false)) {
                            if ($servidorResponsavelIsNewRecord) {
                                $newAcaoServidor = new AcaoServidor();
                                $newAcaoServidor->servidor_id = $modelServidor->id;
                                $newAcaoServidor->acao_id = $modelAcao->id;
                                $newAcaoServidor->saveAudit = Status::STATUS_SIM;
                                $newAcaoServidor->tipo = AcaoServidor::TIPO_RESPONSAVEL;

                                if (! ($flag = $newAcaoServidor->save())) {
                                    $transaction->rollBack();
                                }
                            }
                        }

                        if (!empty($clearDeletedEnvolvidoIds)) {
                            foreach ($clearDeletedEnvolvidoIds as $deletedEnvolvidoId) {
                                $deletedEnvolvido = Servidor::findOne($deletedEnvolvidoId);

                                foreach ($deletedEnvolvido->attributes() as $field) {
                                    if ($field === 'id' || $field === 'created_at') {
                                        continue;
                                    }

                                    $servidorHistorico = new Historico();
                                    $servidorHistorico->action = Historico::ACTION_DELETE;
                                    $servidorHistorico->model = $deletedEnvolvido->formName();
                                    $servidorHistorico->id_registro = $deletedEnvolvido->id;
                                    $servidorHistorico->campo = $field;
                                    $servidorHistorico->antigo_valor = (string) $deletedEnvolvido->$field;
                                    $servidorHistorico->multiple = Status::STATUS_NAO;
                                    $servidorHistorico->usuario_id = User::getIdentidade('id');
                                    $servidorHistorico->usuario_perfil = User::getPerfil();

                                    $servidorHistorico->save();
                                }

                                $deletedAcaoServidor = $deletedEnvolvido->acaoServidor;

                                foreach ($deletedAcaoServidor->attributes() as $field) {
                                    if ($field === 'id') {
                                        continue;
                                    }

                                    $acaoServidorHistorico = new Historico();
                                    $acaoServidorHistorico->action = Historico::ACTION_DELETE;
                                    $acaoServidorHistorico->model = $deletedAcaoServidor->formName();
                                    $acaoServidorHistorico->id_registro = $deletedAcaoServidor->id;
                                    $acaoServidorHistorico->campo = $field;
                                    $acaoServidorHistorico->antigo_valor = (string) $deletedAcaoServidor->$field;
                                    $acaoServidorHistorico->multiple = Status::STATUS_NAO;
                                    $acaoServidorHistorico->usuario_id = User::getIdentidade('id');
                                    $acaoServidorHistorico->usuario_perfil = User::getPerfil();

                                    $acaoServidorHistorico->save();
                                }
                            }

                            Servidor::deleteAll(['id' => $deletedEnvolvidoIds]);
                        }

                        foreach ($modelsServidor as $servidor) {
                            $servidorsEnvolvidoIsNewRecord = $servidor->isNewRecord;

                            $servidor->saveAudit = Status::STATUS_SIM;

                            if (! ($flag = $servidor->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                            if ($servidorsEnvolvidoIsNewRecord) {
                                $newAcaoServidor = new AcaoServidor();
                                $newAcaoServidor->servidor_id = $servidor->id;
                                $newAcaoServidor->acao_id = $modelAcao->id;
                                $newAcaoServidor->saveAudit = Status::STATUS_SIM;
                                $newAcaoServidor->tipo = AcaoServidor::TIPO_ENVOLVIDO;

                                if (! ($flag = $newAcaoServidor->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
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
        }
    }
}
