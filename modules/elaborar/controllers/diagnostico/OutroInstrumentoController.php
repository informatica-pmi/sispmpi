<?php

namespace app\modules\elaborar\controllers\diagnostico;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use Exception;
use yii\helpers\Json;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\Instrumento;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;

/**
 * OutroInstrumentoController implements the CRUD actions for Eixo model.
 */
class OutroInstrumentoController extends Controller
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
     * Cria um ou vários Instrumento
     * Se a criação for um sucesso,o browser te redirecionará para a pagina 'update'
     * @return mixed
     */
    public function actionCreate()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'status' => Status::PLANO_ELABORACAO,
            'orgao_id' => $userOrgaoId
        ]);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }
        
        $modelsInstrumento = [new Instrumento()];

        $isPost = Yii::$app->request->isPost;

        $instrumentosIdsSave = [];

        if ($isPost) {
            $modelsInstrumento = Model::createMultiple(Instrumento::className());
            Model::loadMultiple($modelsInstrumento, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsInstrumento);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsInstrumento as $instrumento) {
                        $instrumento->orgao_id = User::getIdentidade('orgao_id');

                        if (! ($flag = $instrumento->save(false))) {
                            $transaction->rollBack();
                            break;
                        }

                        array_push($instrumentosIdsSave, (string) $instrumento->id);
                    }

                    if ($flag) {
                        $transaction->commit();
                        return Json::encode($instrumentosIdsSave);
                    }
                } catch (Exception $exception) {
                    $transaction->rollBack();
                    return 0;
                }
            }
        }

        return $this->renderAjax('/diagnostico/instrumento/outro/_form', [
            'modelsInstrumento' => (empty($modelsInstrumento)) ? [new Instrumento()] : $modelsInstrumento,
        ]);
    }
}
