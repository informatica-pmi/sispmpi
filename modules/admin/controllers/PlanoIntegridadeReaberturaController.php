<?php

namespace app\modules\admin\controllers;

use app\components\helpers\Universal;
use app\models\pesquisa\PlanoIntegridadeSearch;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\admin\models\Orgao;
use app\modules\admin\models\PlanoIntegridadeReabertura;
use Exception;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Undocumented class
 */
class PlanoIntegridadeReaberturaController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * List all planos with status PUBLICADO
     *
     * @return void
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('plano-integridade-reabertura')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsOrgao = Orgao::find()
            ->innerJoinWith(['planoIntegridades'])
            ->where(['orgao.status' => Status::STATUS_ATIVO])
            ->all();

        $orgaoIds = ArrayHelper::getColumn($modelsOrgao, 'id');

        $subQuery = PlanoIntegridade::find()
            ->select('MAX(id) as id')
            ->where(['in', 'orgao_id', $orgaoIds])
            ->groupBy(['orgao_id']);

        $query = PlanoIntegridade::find()
            ->where(['in', 'id', $subQuery])
            ->andWhere(['status' => Status::PLANO_PUBLICADO]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create new reopeing of module 01
     *
     * @param integer $planoId Identifier number of PlanoIntegridade.
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        if (!Universal::temPermissao('plano-integridade-reabertura')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelPlano = PlanoIntegridade::findOne($planoId);
        $modelPlano->status = Status::PLANO_ELABORACAO;

        $model = new PlanoIntegridadeReabertura();
        $model->plano_integridade_id = $modelPlano->id;
        $model->usuario_id = User::getIdentidade('id');

        if ($model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($flag = $model->save(false)) {
                    $modelPublicacao = $modelPlano->publicacao;

                    if ($modelPublicacao) {
                        $arquivoIds = array_filter([
                            $modelPublicacao->plano_comunicacao_arquivo,
                            $modelPublicacao->plano_treinamento_arquivo,
                            $modelPublicacao->plano_acao_arquivo,
                            $modelPublicacao->plano_integridade_arquivo
                        ]);

                        if (! ($flag = $modelPublicacao->delete())) {
                            $transaction->rollBack();
                        }

                        foreach ($arquivoIds as $arquivoId) {
                            if (! ($flag = Universal::deleteArquivo($arquivoId))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if (! ($flag = $modelPlano->save())) {
                        $transaction->rollBack();
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();
                        return $this->redirect(['index']);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        Universal::flash('error', 'Erro ao reabrir o módulo 01.');
        return $this->render(['index']);
    }
}
