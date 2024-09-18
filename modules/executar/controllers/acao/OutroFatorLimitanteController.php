<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\FatorLimitante;
use app\models\User;

/**
 * OutroFatorLimitante implements the CRUD actions for Eixo model.
 */
class OutroFatorLimitanteController extends Controller
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
     * Cria um ou varios FatorLimitante
     * Se a criação for um sucesso, o browser te redirecionará para a página 'acao/view'
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('modulo-execucao')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsFatorLimitante = [new FatorLimitante()];

        $modelsFatorLimitante = Model::createMultiple(FatorLimitante::className());
        $fatoresLimitantesIdsSave = [];

        if (Model::loadMultiple($modelsFatorLimitante, Yii::$app->request->post())) {
            $valid = Model::validateMultiple($modelsFatorLimitante);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsFatorLimitante as $modelFatorLimitante) {
                        $modelFatorLimitante->orgao_id = User::getIdentidade('orgao_id');

                        if (! ($flag = $modelFatorLimitante->save(false))) {
                            $transaction->rollBack();
                            break;
                        }

                        array_push($fatoresLimitantesIdsSave, (string) $modelFatorLimitante->id);
                    }

                    if ($flag) {
                        $transaction->commit();
                        return Json::encode($fatoresLimitantesIdsSave);
                    }
                } catch (Exception $exception) {
                    $transaction->rollBack();
                    return 0;
                }
            }
        }

        return $this->renderAjax('/acao/execucao/outro-fator-limitante/_form', [
            'modelsFatorLimitante' => (empty($modelsFatorLimitante)) ? [new FatorLimitante()] : $modelsFatorLimitante,
        ]);
    }
}
