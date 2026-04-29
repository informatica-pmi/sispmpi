<?php

namespace app\modules\elaborar\controllers\validacao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use app\base\Model;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Stakeholder;
use app\models\Status;
use app\models\User;

/**
 * OutroStakeholderController implements the CRUD actions for Eixo model.
 */
class OutroStakeholderController extends Controller
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
     * Cria um ou varios Stakeholder
     * Se a criação for um sucesso, o browser te redirecionará para a página 'create' ou 'update'
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

        $modelsStakeholder = [new Stakeholder()];

        $modelsStakeholder = Model::createMultiple(Stakeholder::className());
        $stakeholdersIdsSave = [];

        if (Model::loadMultiple($modelsStakeholder, Yii::$app->request->post())) {
            $valid = Model::validateMultiple($modelsStakeholder);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($modelsStakeholder as $stakeholder) {
                        $stakeholder->orgao_id = User::getIdentidade('orgao_id');

                        if (! ($flag = $stakeholder->save(false))) {
                            $transaction->rollBack();
                            break;
                        }

                        array_push($stakeholdersIdsSave, (string) $stakeholder->id);
                    }

                    if ($flag) {
                        $transaction->commit();
                        return Json::encode($stakeholdersIdsSave);
                    }
                } catch (Exception $exception) {
                    $transaction->rollBack();
                    return 0;
                }
            }
        }

        return $this->renderAjax('_form', [
            'modelsStakeholder' => (empty($models)) ? [new Stakeholder()] : $modelsStakeholder,
        ]);
    }
}
