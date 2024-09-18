<?php

namespace app\modules\elaborar\controllers\diagnostico;

use app\base\Model;
use app\components\helpers\Universal;
use app\models\EixoTematico;
use app\models\Instrumento;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;

class OutroEixoTematicoController extends Controller
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

        $modelsEixoTematico = [new EixoTematico()];

        $isPost = Yii::$app->request->isPost;

        $eixosTematicosIdsSave = [];

        if ($isPost) {
            $modelsEixoTematico = Model::createMultiple(EixoTematico::className());
            Model::loadMultiple($modelsEixoTematico, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsEixoTematico);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsEixoTematico as $eixoTematico) {
                        $eixoTematico->orgao_id = $userOrgaoId;

                        if (! ($flag = $eixoTematico->save(false))) {
                            $transaction->rollBack();
                            break;
                        }

                        $eixosTematicosIdsSave[] = (string)$eixoTematico->id;
                    }

                    if ($flag) {
                        $transaction->commit();
                        return Json::encode($eixosTematicosIdsSave);
                    }
                } catch (Exception $exception) {
                    $transaction->rollBack();
                    return 0;
                }
            }
        }

        return $this->renderAjax('/diagnostico/resultado/outro-eixo-tematico/_form', [
            'modelsEixoTematico' => (empty($modelsEixoTematico)) ? [new EixoTematico()] : $modelsEixoTematico,
        ]);
    }
}
