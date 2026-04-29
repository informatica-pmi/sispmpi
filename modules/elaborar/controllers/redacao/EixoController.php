<?php

namespace app\modules\elaborar\controllers\redacao;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\modules\elaborar\models\Eixo;

/**
 * EixoController implements the CRUD actions for Eixo model.
 */
class EixoController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Cria um novo Eixo
     * Se a criação for um sucesso, o browser te redirecionará para a pagina 'create'
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelEixo = new Eixo();

        $modelEixo->plano_integridade_id = $modelPlano->id;

        if ($modelEixo->load(Yii::$app->request->post()) && $modelEixo->save()) {
            if ($modelPlano->status == Status::PLANO_N_INICIADO) {
                $modelPlano->status = Status::PLANO_ELABORACAO;
                $modelPlano->save();
            }

            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $modelEixo->plano_integridade_id,
                'key' => 'eixo',
            ]);
        }
    }

    /**
     * Atualiza um eixo existente
     * Se a criação for um sucesso, o browser te redirecionará para a pagina 'create'
     *
     * @param integer $eixoId
     * @return void
     */
    public function actionUpdate($eixoId)
    {
        $modelEixo = $this->findModel($eixoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelEixo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if ($modelEixo->load(Yii::$app->request->post()) && $modelEixo->save()) {
            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $modelEixo->plano_integridade_id,
                'key' => 'eixo',
            ]);
        }
    }

    /**
     * Apaga uma Eixo existente
     * Se a exclusão for um sucesso, o browser te redirecionará para a pagina 'create'
     * @param integer $eixoId Número identificador do Eixo
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($eixoId)
    {
        $model = $this->findModel($eixoId);

        $planoId = $model->plano_integridade_id;

        if ($model->delete()) {
            Universal::flash();

            return $this->redirect(['@elaborar/redacao/default/create', 'planoId' => $planoId]);
        }
    }

    /**
     * Finds the Eixo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Eixo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Eixo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
