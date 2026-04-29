<?php

namespace app\modules\elaborar\controllers\validacao;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\helpers\Mail;
use app\components\helpers\OrganSpecific;
use app\components\helpers\Universal;
use app\models\Stakeholder;
use app\models\User;
use app\modules\elaborar\models\Validacao;
use app\modules\elaborar\models\ValidacaoStakeholder;
use app\models\Status;
use app\models\PlanoIntegridade;

/**
 * DefaultController implements the CRUD actions for Validacao model.
 */
class DefaultController extends Controller
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
     * Cria uma nova Validacao
     * Se a criação for um sucesso, o browser te redirecionará para a página 'elaborar/index'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelValidacao = new Validacao();

        $optionsStakeholder = OrganSpecific::search(Stakeholder::className());

        if ($modelValidacao->load(Yii::$app->request->post())) {
            $modelPlano->status = Status::PLANO_ELABORACAO;

            $modelValidacao->plano_integridade_id = $planoId;

            $valid = $modelValidacao->validate();
            $valid = $modelPlano->validate() && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelValidacao->save(false) && $modelPlano->save(false)) {
                        foreach ($modelValidacao->stakeholderIds as $stakeholderId) {
                            $newValidacaoStakeholder = new ValidacaoStakeholder();
                            $newValidacaoStakeholder->validacao_id = $modelValidacao->id;
                            $newValidacaoStakeholder->stakeholder_id = $stakeholderId;

                            if (! ($flag = $newValidacaoStakeholder->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();

                        $this->sendMail();

                        return $this->redirect(['@elaborar']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelValidacao' => $modelValidacao,
            'optionsStakeholder' => $optionsStakeholder,
        ]);
    }

    /**
     * Atualiza uma Validação existente
     * Se a atualização for um sucesso, o navegador te redirecionará para a página 'elaborar/index'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        $hasDefaultModulePermission = Universal::temPermissao('preencher-elaboracao', $modelPlano);

        if (!$hasDefaultModulePermission || !is_null($modelPlano->plano_integridade_referencia_id)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelValidacao = $modelPlano->validacao;
        $modelValidacao->stakeholderIds = ArrayHelper::getColumn(
            $modelValidacao->validacaoStakeholders,
            'stakeholder_id'
        );

        $optionsStakeholder = OrganSpecific::search(Stakeholder::className());

        if ($modelValidacao->load(Yii::$app->request->post())) {
            $valid = $modelValidacao->validate();

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    ValidacaoStakeholder::deleteAll(['validacao_id' => $modelValidacao->id]);

                    if ($flag = $modelValidacao->save(false)) {
                        foreach ($modelValidacao->stakeholderIds as $stakeholderId) {
                            $newValidacaoStakeholder = new ValidacaoStakeholder();
                            $newValidacaoStakeholder->validacao_id = $modelValidacao->id;
                            $newValidacaoStakeholder->stakeholder_id = $stakeholderId;

                            if (! ($flag = $newValidacaoStakeholder->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Universal::flash();

                        $this->sendMail();

                        return $this->redirect(['@elaborar']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelValidacao' => $modelValidacao,
            'optionsStakeholder' => $optionsStakeholder,
        ]);
    }

    /**
     * Enviando o e-mail para todos os auditores cadastrados no sistema vinculado ao órgao
     * @return bool
     */
    protected function sendMail()
    {
        $users = User::find()
            ->joinWith(['authAssignment'])
            ->where([
                'item_name' => User::PERFIL_AUDITOR,
                'orgao_id' => User::getIdentidade('orgao_id'),
                'status' => Status::STATUS_ATIVO
            ])
            ->all();

        if ($users) {
            Mail::sendMultiple('./elaborar/validacao-plano', null, $users, 'Plano de integridade validado');
        }

        return true;
    }
}
