<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use Exception;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\Tipo;
use app\models\User;

/**
 * OutroTipoController implements the CRUD actions for Eixo model.
 */
class OutroTipoController extends Controller
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
     * Cria um ou vários Tipo
     * Se a criação for um sucesso,o browser te redirecionará para a pagina 'acao/view'
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Universal::temPermissao('modulo-execucao')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsTipo = [new Tipo()];

        $modelsTipo = Model::createMultiple(Tipo::className());

        $tiposIdsSave = [];

        if (Model::loadMultiple($modelsTipo, Yii::$app->request->post())) {
            $valid = Model::validateMultiple($modelsTipo);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsTipo as $modelTipo) {
                        $modelTipo->orgao_id = User::getIdentidade('orgao_id');

                        if (! ($flag = $modelTipo->save(false))) {
                            $transaction->rollBack();
                            break;
                        }

                        array_push($tiposIdsSave, (string) $modelTipo->id);
                    }

                    if ($flag) {
                        $transaction->commit();
                        return Json::encode($tiposIdsSave);
                    }
                } catch (Exception $exception) {
                    $transaction->rollBack();
                    return 0;
                }
            }
        }

        return $this->renderAjax('/acao/informacao/outro-tipo/_form', [
            'modelsTipo' => (empty($modelsTipo)) ? [new Tipo()] : $modelsTipo,
        ]);
    }
}
