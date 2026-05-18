<?php

namespace app\modules\elaborar\controllers\grupo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Servidor;
use app\models\Status;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\modules\elaborar\models\Grupo;
use app\modules\elaborar\models\GrupoInstituido;
use app\modules\elaborar\models\GrupoServidor;

/**
 * DefaultController implements the CRUD actions for Grupo model.
 */
class DefaultController extends Controller
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
     * Cria um novo Grupo, Grupo Instituido e Servidor
     * Se a criação for um sucesso,o browser te redirecionará para a pagina 'elaborar/index'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelGrupo = new Grupo();

        $modelGrupoInstituido = new GrupoInstituido();

        $modelsServidor = [new Servidor()];

        $modelGrupo->plano_integridade_id = $modelPlano->id;

        $modelPlano->status = Status::PLANO_ELABORACAO;

        $optionsUnidadeAdministrativa = ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->all(),
            'id',
            'nome'
        );

        if ($modelGrupoInstituido->load(Yii::$app->request->post())) {
            if ($modelGrupoInstituido->formalmente == Status::STATUS_SIM) {
                $modelGrupoInstituido->data_prevista_conclusao = Universal::addDays(
                    $modelGrupoInstituido->data_publicacao,
                    $modelGrupoInstituido->dias_conclusao
                );
            }

            $modelGrupoInstituido->order = 0;
            $modelGrupoInstituido->tipo = GrupoInstituido::TIPO_GRUPO;

            $modelsServidor = Model::createMultiple(Servidor::className());

            Model::loadMultiple($modelsServidor, Yii::$app->request->post());

            $valid = $modelGrupo->validate();
            $valid = $modelPlano->validate() && $valid;
            $valid = $modelGrupoInstituido->validate() && $valid;
            $valid = Model::validateMultiple($modelsServidor) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelGrupo->save(false) && $modelPlano->save(false)) {
                        $modelGrupoInstituido->grupo_id = $modelGrupo->id;

                        if ($flag = $modelGrupoInstituido->save(false)) {
                            foreach ($modelsServidor as $modelServidor) {
                                if (!($flag = $modelServidor->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }

                                $saveSuccess = $this->saveGrupoServidor(
                                    $modelGrupo->id,
                                    $modelServidor->id,
                                    $modelServidor->coordenador,
                                );

                                if (!($flag = $saveSuccess)) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();

                        return $this->redirect(['@elaborar']);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelGrupo' => $modelGrupo,
            'modelGrupoInstituido' => $modelGrupoInstituido,
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        ]);
    }

    /**
     * Atualiza Grupo, GrupoInstituido e Servidor existente
     * Se a atualização for um sucesso, o browser te redirecionará para a página 'elaborar/index'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelGrupo = $modelPlano->grupo;

        $modelGrupoInstituido = $modelGrupo->getFirstGrupoInstituido()
            ->where(['tipo' => GrupoInstituido::TIPO_GRUPO])
            ->one();

        $modelsGrupoServidor = $modelGrupo->firstGrupoServidors;

        $modelsServidor = Servidor::findAll(['id' => ArrayHelper::getColumn($modelsGrupoServidor, 'servidor_id')]);

        $prepareGrupoServidor = ArrayHelper::getColumn($modelsGrupoServidor, function ($modelGrupoServidor) {
            return $modelGrupoServidor->coordenador === Status::STATUS_SIM ?
                "<strong>{$modelGrupoServidor->servidor->nome}</strong>" :
                "<span>{$modelGrupoServidor->servidor->nome}</span>";
        });

        $coordenadors = array_column($modelsGrupoServidor, 'coordenador', 'servidor_id');

        foreach ($modelsServidor as $modelServidor) {
            $modelServidor->coordenador = $coordenadors[$modelServidor->id];
        }

        $ordersGrupoServidorUpdate = array_values(
            array_unique(
                ArrayHelper::getColumn(
                    $modelGrupo->withoutFirstGrupoServidors,
                    'order'
                )
            )
        );

        $optionsUnidadeAdministrativa = ArrayHelper::map(
            UnidadeAdministrativa::find()
                ->where(['orgao_id' => User::getIdentidade('orgao_id')])
                ->all(),
            'id',
            'nome'
        );

        $modelsGrupoInstituido = $modelGrupo->getGrupoInstituidos()
            ->where(['!=', 'order', 0])
            ->andWhere(['tipo' => GrupoInstituido::TIPO_GRUPO])
            ->all();

        if ($modelGrupoInstituido->load(Yii::$app->request->post())) {
            if ($modelGrupoInstituido->formalmente == Status::STATUS_SIM) {
                $modelGrupoInstituido->data_prevista_conclusao = Universal::addDays(
                    $modelGrupoInstituido->data_publicacao,
                    $modelGrupoInstituido->dias_conclusao
                );
            }

            $oldIds = ArrayHelper::map($modelsServidor, 'id', 'id');
            $modelsServidor = Model::createMultiple(Servidor::className(), $modelsServidor);
            Model::loadMultiple($modelsServidor, Yii::$app->request->post());
            $deletedIds = array_diff($oldIds, array_filter(ArrayHelper::map($modelsServidor, 'id', 'id')));

            $valid = $modelGrupoInstituido->validate();
            $valid = Model::validateMultiple($modelsServidor) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelGrupoInstituido->save(false)) {
                        if (!empty($deletedIds)) {
                            Servidor::deleteAll(['id' => $deletedIds]);
                        }

                        foreach ($modelsServidor as $modelServidor) {
                            if (!($flag = $modelServidor->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                            $saveSuccess = $this->saveGrupoServidor(
                                $modelGrupo->id,
                                $modelServidor->id,
                                $modelServidor->coordenador,
                            );

                            if (!($flag = $saveSuccess)) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();

                        return $this->redirect(['@elaborar']);
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelGrupo' => $modelGrupo,
            'modelGrupoInstituido' => $modelGrupoInstituido,
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
            'modelsGrupoInstituido' => $modelsGrupoInstituido,
            'prepareGrupoServidor' => $prepareGrupoServidor,
            'ordersGrupoServidorUpdate' => $ordersGrupoServidorUpdate,
            'optionsUnidadeAdministrativa' => $optionsUnidadeAdministrativa,
        ]);
    }

    /**
     * Salvando GrupoServidor
     * @param integer $grupoId
     * @param integer $servidor
     * @param integer $status
     * @param integer $coordenador
     * @param integer $maxOrder
     */
    protected function saveGrupoServidor($grupoId, $servidor, $coordenador)
    {
        $modelGrupoServidor = GrupoServidor::findOne([
            'grupo_id' => $grupoId,
            'servidor_id' => $servidor,
            'order' => 0
        ]) ?? new GrupoServidor();

        $modelGrupoServidor->grupo_id = $grupoId;
        $modelGrupoServidor->servidor_id = $servidor;
        $modelGrupoServidor->status = Status::STATUS_ATIVO;
        $modelGrupoServidor->coordenador = $coordenador;
        $modelGrupoServidor->order = 0;

        return $modelGrupoServidor->save();
    }


    /**
     * Finds the Grupo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grupo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grupo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
