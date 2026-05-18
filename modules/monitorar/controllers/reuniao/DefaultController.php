<?php

namespace app\modules\monitorar\controllers\reuniao;

use Yii;
use Exception;
use app\base\Model;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\components\helpers\Dynamic;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Servidor;
use app\models\Status;
use app\models\User;
use app\modules\monitorar\models\Reuniao;
use app\modules\monitorar\models\pesquisa\ReuniaoSearch;
use app\modules\monitorar\models\ReuniaoServidor;

/**
 * DefaultController implements the CRUD actions for Reuniao model.
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
     * Lista todas as reuniões
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModel = new ReuniaoSearch();

        $searchModel->plano_integridade_id = $modelPlano->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $dataProvider->getTotalCount(),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Creates a new Reuniao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $planoId Identificado do plano de integridade
     * @return mixed
     */
    public function actionCreate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelReuniao = new Reuniao();

        $modelsServidor = [new Servidor()];

        Dynamic::scenario($modelsServidor, Servidor::SCENARIO_REUNIAO);

        if ($modelReuniao->load(Yii::$app->request->post())) {
            $modelsServidor = Model::createMultiple(Servidor::className());
            Dynamic::scenario($modelsServidor, Servidor::SCENARIO_REUNIAO);
            Model::loadMultiple($modelsServidor, Yii::$app->request->post());

            $modelReuniao->plano_integridade_id = $modelPlano->id;
            $modelReuniao->usuario_id = User::getIdentidade('id');

            $valid = $modelReuniao->validate();
            $valid = Model::validateMultiple($modelsServidor) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelReuniao->save(false)) {
                        foreach ($modelsServidor as $modelServidor) {
                            if (! ($flag = $modelServidor->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                            $modelReuniaoServidor = new ReuniaoServidor();
                            $modelReuniaoServidor->reuniao_id = $modelReuniao->id;
                            $modelReuniaoServidor->servidor_id = $modelServidor->id;

                            if (! ($flag = $modelReuniaoServidor->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->renderAjax('create', [
            'modelReuniao' => $modelReuniao,
            'modelsServidor' => (empty($modelsServidor)) ? [new Servidor()] : $modelsServidor,
        ]);
    }

    /**
     * Finds the Reuniao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reuniao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reuniao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
