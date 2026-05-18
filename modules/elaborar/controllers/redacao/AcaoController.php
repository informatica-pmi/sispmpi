<?php

namespace app\modules\elaborar\controllers\redacao;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Status;
use app\models\AcaoUnidadeApoio;
use app\models\PlanoIntegridade;

/**
 * AcaoController implements the CRUD actions for Acao model.
 */
class AcaoController extends Controller
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
     * Cria uma nova Acao
     * Se a criação for um sucesso, o browser te redirecionará para a página 'create'
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelAcao = new Acao();

        if ($modelAcao->load(Yii::$app->request->post())) {
            $modelAcao->status = Status::ACAO_N_INICIALIZADA;

            if ($modelAcao->save()) {
                if (!empty($modelAcao->unidadeApoioIds)) {
                    foreach ($modelAcao->unidadeApoioIds as $unidadeApoioId) {
                        $newUnidadeApoio = new AcaoUnidadeApoio();
                        $newUnidadeApoio->acao_id = $modelAcao->id;
                        $newUnidadeApoio->unidade_administrativa_id = $unidadeApoioId;
                        $newUnidadeApoio->save();
                    }
                }

                Universal::flash();

                return $this->redirect([
                    '@elaborar/redacao/default/create',
                    'planoId' => $modelAcao->eixo->plano_integridade_id,
                    'key' => 'acao'
                ]);
            }
        }
    }

    /**
     * Atualiza uma acao existente
     * Se a atualizacao for um sucesso, o browser te redirecionará para a página 'create'
     *
     * @param integer $acaoId
     * @return void
     */
    public function actionUpdate($acaoId)
    {
        $modelAcao = $this->findModel($acaoId);

        $modelEixo = $modelAcao->eixo;

        if (!Universal::temPermissao('preencher-elaboracao', $modelEixo->planoIntegridade)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $oldUnidadeApoioIds = ArrayHelper::getColumn($modelAcao->acaoUnidadeApoios, 'id');

        if ($modelAcao->load(Yii::$app->request->post()) && $modelAcao->save()) {
            if (!empty($oldUnidadeApoioIds)) {
                AcaoUnidadeApoio::deleteAll(['id' => $oldUnidadeApoioIds]);
            }

            if (!empty($modelAcao->unidadeApoioIds)) {
                foreach ($modelAcao->unidadeApoioIds as $unidadeApoioId) {
                    $newUnidadeApoio = new AcaoUnidadeApoio();
                    $newUnidadeApoio->acao_id = $modelAcao->id;
                    $newUnidadeApoio->unidade_administrativa_id = $unidadeApoioId;
                    $newUnidadeApoio->save();
                }
            }

            Universal::flash();

            return $this->redirect([
                '@elaborar/redacao/default/create',
                'planoId' => $modelEixo->plano_integridade_id,
                'key' => 'acao'
            ]);
        }
    }

    /**
     * Apaga uma Acao existente
     * Se a exclusão for um sucesso, o browser te redirecionará para a pagina 'create'
     * @param integer $acaoId Número identificador da Ação
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($acaoId)
    {
        $model = $this->findModel($acaoId);

        $planoId = $model->eixo->plano_integridade_id;

        if ($model->delete()) {
            Universal::flash();

            return $this->redirect([
            '@elaborar/redacao/default/create',
            'planoId' => $planoId,
            'key' => 'acao',
            ]);
        }
    }

    /**
     * Finds the Acao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Acao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Acao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
