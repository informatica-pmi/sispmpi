<?php

namespace app\modules\elaborar\controllers\grupo;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\modules\elaborar\models\GrupoInstituido;
use app\modules\elaborar\models\Grupo;

/**
 * InstituidoController implements the CRUD actions for GrupoInstituido model.
 */
class InstituidoController extends Controller
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
     * Cria um novo GrupoInstituido
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

        $modelGrupoInstituido = new GrupoInstituido();

        $countGrupoInstituido = GrupoInstituido::find()->where(['grupo_id' => $grupoId])->count();

        $modelGrupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL;

        if ($modelGrupoInstituido->load(Yii::$app->request->post())) {
            $modelGrupoInstituido->grupo_id = $grupoId;

            $modelGrupoInstituido->order = $countGrupoInstituido;

            $modelGrupoInstituido->data_prevista_conclusao = Universal::addDays(
                $modelGrupoInstituido->data_publicacao,
                $modelGrupoInstituido->dias_conclusao
            );

            $modelGrupoInstituido->tipo = GrupoInstituido::TIPO_GRUPO;

            if ($modelGrupoInstituido->save()) {
                Universal::flash();

                return $this->redirect([
                    '@elaborar/grupo/default/update',
                    'planoId' => $modelGrupo->plano_integridade_id
                ]);
            }
        }

        return $this->renderAjax('create', [
            'modelGrupoInstituido' => $modelGrupoInstituido,
        ]);
    }

    /**
     * Atualiza um GrupoInstituido model existente
     * Se a atualização for um sucesso, o browser te redirecionará para a página 'grupo/update'
     *
     * @param integer $grupoId Número identificador do Grupo
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($grupoId, $order)
    {
        $modelGrupo = Grupo::findOne($grupoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelGrupo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelGrupoInstituido = GrupoInstituido::findOne([
            'grupo_id' => $grupoId, 
            'order' => $order,
            'tipo' => GrupoInstituido::TIPO_GRUPO
        ]);

        $modelGrupoInstituido->scenario = GrupoInstituido::SCENARIO_ADITIONAL;

        if ($modelGrupoInstituido->load(Yii::$app->request->post())) {
            $modelGrupoInstituido->data_prevista_conclusao = Universal::addDays(
                $modelGrupoInstituido->data_publicacao,
                $modelGrupoInstituido->dias_conclusao
            );

            if ($modelGrupoInstituido->save()) {
                Universal::flash();

                return $this->redirect([
                    '@elaborar/grupo/default/update',
                    'planoId' => $modelGrupo->plano_integridade_id
                ]);
            }
        }

        return $this->renderAjax('update', [
            'modelGrupoInstituido' => $modelGrupoInstituido,
        ]);
    }

    /**
     * Finds the GrupoInstituido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrupoInstituido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrupoInstituido::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
