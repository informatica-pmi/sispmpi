<?php

namespace app\modules\elaborar\controllers\redacao;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\modules\elaborar\models\Subeixo;

/**
 * SubeixoController implements the CRUD actions for Subeixo model.
 */
class SubeixoController extends Controller
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
     * Cria um novo Subeixo
     * Se a criação for um sucesso, o browser te redirecionará para a página 'create'
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelSubeixo = new Subeixo();

        if ($modelSubeixo->load(Yii::$app->request->post()) && $modelSubeixo->save()) {
            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $modelPlano->id,
                'key' => 'subeixo'
            ]);
        }
    }

    /**
     * Atualiza um Subeixo existente
     * Se a atualização for um sucesso, o browser te redirecionará para a página 'create'
     *
     * @param integer $subeixoId
     * @return void
     */
    public function actionUpdate($subeixoId)
    {
        $modelSubeixo = $this->findModel($subeixoId);

        $modelEixo = $modelSubeixo->eixo;

        if (!Universal::temPermissao('preencher-elaboracao', $modelEixo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if ($modelSubeixo->load(Yii::$app->request->post()) && $modelSubeixo->save()) {
            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $modelEixo->plano_integridade_id,
                'key' => 'subeixo'
            ]);
        }
    }

    /**
     * Apaga uma Subeixo existente
     * Se a exclusão for um sucesso, o browser te redirecionará para a pagina 'create'
     * @param integer $subeixoId Número identificador do subeixo
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($subeixoId)
    {
        $model = $this->findModel($subeixoId);

        $planoId = $model->eixo->plano_integridade_id;

        if ($model->delete()) {
            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $planoId,
                'key' => 'subeixo',
            ]);
        }
    }

    /**
     * Listando parents do eixo selecionado, extensão DepDrop
     * @return array
     */
    public function actionList()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = [];

        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $eixoId = $parents[0];
                $out = self::getSubeixo($eixoId);
                return [
                    'output' => $out,
                    'selected' => ''
                ];
            }
        }

        return [
            'output' => '',
            'selected' => ''
        ];
    }

    /**
     * Search in database list of subeixos
     * @param integer $eixoId Número identificador do eixo
     * @return array
     */
    protected static function getSubeixo($eixoId)
    {
        $models = Subeixo::find()
            ->where(['eixo_id' => $eixoId])
            ->orderBy(['titulo' => 'SORT_ASC'])
            ->all();

        return $models;
    }

    /**
     * Finds the Subeixo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subeixo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subeixo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
