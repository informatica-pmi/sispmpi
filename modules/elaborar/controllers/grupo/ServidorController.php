<?php

namespace app\modules\elaborar\controllers\grupo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\Servidor;
use app\models\Status;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\modules\elaborar\models\Grupo;
use app\modules\elaborar\models\GrupoInstituido;
use app\modules\elaborar\models\GrupoServidor;

/**
 * ServidorController implements the CRUD actions for Servidor model.
 */
class ServidorController extends Controller
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
     * Cria um novo Servidor
     * Se a criação for um sucesso, o browser te redirecionará para a página 'grupo/update'
     *
     * @param integer $grupoId Número identificador do Grupo
     * @return mixed
     */
    public function actionCreate($grupoId)
    {
        $modelGrupo = Grupo::findOne($grupoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelGrupo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsServidor = [new Servidor()];
        $modelGrupoInstituido = new GrupoInstituido();
        $modelGrupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL_SERVIDOR;

        $maxOrder = GrupoServidor::find()->where(['grupo_id' => $grupoId])->max("`order`");

        $newOrder = $maxOrder + 1;

        $modelsGrupoServidor = GrupoServidor::find()
            ->where([
                'grupo_id' => $grupoId,
                'order' => $maxOrder,
                'status' => Status::STATUS_ATIVO
            ])
            ->all();

        $optionsUnidadeAdministrativa = ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->all(),
            'id',
            'nome'
        );

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $modelsServidor = Model::createMultiple(Servidor::className());
            Model::loadMultiple($modelsServidor, Yii::$app->request->post());

            $modelsGrupoServidor = Model::createMultiple(GrupoServidor::className());
            Model::loadMultiple($modelsGrupoServidor, Yii::$app->request->post());

            $modelGrupoInstituido->load(Yii::$app->request->post());
            $modelGrupoInstituido->grupo_id = $grupoId;
            $modelGrupoInstituido->order = $newOrder;
            $modelGrupoInstituido->tipo = GrupoInstituido::TIPO_SERVIDOR;

            $valid = Model::validateMultiple($modelsServidor);
            $valid = Model::validateMultiple($modelsGrupoServidor) && $valid;
            $valid = $modelGrupoInstituido->validate() && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelGrupoInstituido->save(false)) {
                        foreach ($modelsServidor as $modelServidor) {
                            if (!($flag = $modelServidor->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                            $saveSuccess = $this->saveGrupoServidor(
                                $grupoId,
                                $modelServidor->id,
                                Status::STATUS_ATIVO,
                                $modelServidor->coordenador,
                                $newOrder,
                            );

                            if (!($flag = $saveSuccess)) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        foreach ($modelsGrupoServidor as $modelGrupoServidor) {
                            $saveSuccess = $this->saveGrupoServidor(
                                $grupoId,
                                $modelGrupoServidor->servidor_id,
                                $modelGrupoServidor->status,
                                $modelGrupoServidor->coordenador,
                                $newOrder
                            );

                            if (!($flag = $saveSuccess)) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Universal::flash();

                            return $this->redirect([
                                '@elaborar/grupo/default/update',
                                'planoId' => $modelGrupo->plano_integridade_id
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->renderAjax('create', [
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
            'modelsGrupoServidor' => $modelsGrupoServidor,
            'modelGrupoInstituido' => $modelGrupoInstituido,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        ]);
    }

    /**
     * Atualiza um Servidor existente
     * Se a atualização for um sucesso, o browser te redirecionará para a pagina 'grupo/update'
     *
     * @param integer $grupoId Número identificador do Grupo
     * @param integer $order
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($grupoId, $order)
    {
        $modelGrupo = Grupo::findOne($grupoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelGrupo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $oldOrder = $order - 1;

        $modelGrupoInstituido = GrupoInstituido::findOne([
            'grupo_id' => $grupoId, 
            'order' => $order,
            'tipo' => GrupoInstituido::TIPO_SERVIDOR
        ]);
        $modelGrupoInstituido = !empty($modelGrupoInstituido) ? $modelGrupoInstituido : new GrupoInstituido();
        $modelGrupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL_SERVIDOR;

        $modelsGrupoServidor = GrupoServidor::find()->where(['grupo_id' => $grupoId, 'order' => $oldOrder])->all();

        $updateServidors = GrupoServidor::find()->where(['grupo_id' => $grupoId, 'order' => $order])->all();

        $searchIds = array_diff(
            ArrayHelper::getColumn(
                $updateServidors,
                'servidor_id'
            ),
            ArrayHelper::getColumn(
                $modelsGrupoServidor,
                'servidor_id'
            )
        );

        $modelsServidor = Servidor::find()
            ->where(['id' => $searchIds])
            ->all();

        $statusGrupoServidor = array_column($updateServidors, 'status', 'servidor_id');

        $coordenadorsGrupoServidor = array_column($updateServidors, 'coordenador', 'servidor_id');

        foreach ($modelsGrupoServidor as $modelGrupoServidor) {
            $modelGrupoServidor->status = array_key_exists($modelGrupoServidor->servidor_id, $statusGrupoServidor) ?
                $statusGrupoServidor[$modelGrupoServidor->servidor_id] :
                $modelGrupoServidor->status;

            $modelGrupoServidor->coordenador = array_key_exists(
                $modelGrupoServidor->servidor_id,
                $coordenadorsGrupoServidor
            ) ?
                $coordenadorsGrupoServidor[$modelGrupoServidor->servidor_id] :
                $modelGrupoServidor->coordenador;
        }

        $coordenadors = array_column($updateServidors, 'coordenador', 'servidor_id');

        foreach ($modelsServidor as $modelServidor) {
            $modelServidor->coordenador = $coordenadors[$modelServidor->id];
        }

        $optionsUnidadeAdministrativa = ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->all(),
            'id',
            'nome'
        );

        $isPost = Yii::$app->request->isPost;

        if ($isPost) {
            $oldIds = ArrayHelper::map($modelsServidor, 'id', 'id');
            $modelsServidor = Model::createMultiple(Servidor::classname(), $modelsServidor);
            Model::loadMultiple($modelsServidor, Yii::$app->request->post());
            $deletedIds = array_diff($oldIds, array_filter(ArrayHelper::map($modelsServidor, 'id', 'id')));

            $modelsGrupoServidor = Model::createMultiple(GrupoServidor::className());
            Model::loadMultiple($modelsGrupoServidor, Yii::$app->request->post());

            $modelGrupoInstituido->load(Yii::$app->request->post());
            $modelGrupoInstituido->grupo_id = $grupoId;
            $modelGrupoInstituido->order = $order;
            $modelGrupoInstituido->tipo = GrupoInstituido::TIPO_SERVIDOR;

            $valid = Model::validateMultiple($modelsServidor);
            $valid = Model::validateMultiple($modelsGrupoServidor) && $valid;
            $valid = $modelGrupoInstituido->validate() && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelGrupoInstituido->save(false)) {
                        if (!empty($deletedIds)) {
                            GrupoServidor::deleteAll([
                                'grupo_id' => $grupoId,
                                'servidor_id' => $deletedIds,
                                'order' => $order
                            ]);
                        }

                        foreach ($modelsServidor as $modelServidor) {
                            if (!($flag = $modelServidor->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                            $saveSuccess = $this->saveGrupoServidor(
                                $grupoId,
                                $modelServidor->id,
                                Status::STATUS_ATIVO,
                                $modelServidor->coordenador,
                                $order,
                            );

                            if (!($flag = $saveSuccess)) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        foreach ($modelsGrupoServidor as $oldGrupoServidor) {
                            $saveSuccess = $this->saveGrupoServidor(
                                $grupoId,
                                $oldGrupoServidor->servidor_id,
                                $oldGrupoServidor->status,
                                $oldGrupoServidor->coordenador,
                                $order
                            );

                            if (!($flag = $saveSuccess)) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Universal::flash();

                            return $this->redirect([
                                '@elaborar/grupo/default/update',
                                'planoId' => $modelGrupo->plano_integridade_id
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->renderAjax('update', [
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
            'modelsGrupoServidor' => $modelsGrupoServidor,
            'modelGrupoInstituido' => $modelGrupoInstituido,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        ]);
    }

    /**
     * Salvando um novo grupoServidor
     *
     * @param integer $grupoId
     * @param integer $servidor
     * @param integer $status
     * @param integer $coordenador
     * @param integer $order
     *
     * @return boolean
     */
    protected function saveGrupoServidor($grupoId, $servidorId, $status, $coordenador, $order)
    {
        $modelGrupoServidor = GrupoServidor::findOne([
            'grupo_id' => $grupoId,
            'servidor_id' => $servidorId,
            'order' => $order
        ]) ?? new GrupoServidor();

        $modelGrupoServidor->grupo_id = $grupoId;
        $modelGrupoServidor->servidor_id = $servidorId;
        $modelGrupoServidor->status = $status;
        $modelGrupoServidor->coordenador = $coordenador;
        $modelGrupoServidor->order = $order;

        return $modelGrupoServidor->save();
    }
}
