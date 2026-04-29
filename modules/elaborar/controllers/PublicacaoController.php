<?php

namespace app\modules\elaborar\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\components\helpers\Universal;
use app\components\helpers\Mail;
use app\models\Arquivo;
use app\models\PlanoIntegridade;
use app\modules\elaborar\models\Publicacao;
use app\models\User;
use app\models\Status;

/**
 * PublicacaoController implements the CRUD actions for Publicacao model.
 */
class PublicacaoController extends Controller
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
     * Cria uma nova Publicacao
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

        $modelPublicacao = new Publicacao();

        if ($modelPublicacao->load(Yii::$app->request->post())) {
            $modelPublicacao->plano_integridade_id = $planoId;
            $modelPublicacao->usuario_id = User::getIdentidade('id');

            $modelPublicacao->integridadeFile = UploadedFile::getInstance($modelPublicacao, 'integridadeFile');

            if ($modelPublicacao->integridadeFile) {
                $modelArquivo = new Arquivo();
                $modelArquivo->file = $modelPublicacao->integridadeFile;

                $modelPublicacao->plano_integridade_arquivo = $modelArquivo->upload(
                    'uploads/plano_integridade',
                    $planoId,
                    'publicacao'
                );
            }

            $modelPlano->status = Status::PLANO_PUBLICADO;

            if ($modelPublicacao->save(false) && $modelPlano->save()) {
                $usersCge = User::find()
                    ->joinWith(['authAssignment'])
                    ->where([
                        'item_name' => User::PERFIL_ADMINISTRADOR,
                        'status' => Status::STATUS_ATIVO
                    ])
                    ->all();

                if ($usersCge) {
                    Mail::sendMultiple('./elaborar/cge-publicacao', null, $usersCge, 'Plano de integridade finalizado');
                }

                $usersAuditors = User::find()
                    ->joinWith(['authAssignment'])
                    ->where([
                        'item_name' => User::PERFIL_AUDITOR,
                        'orgao_id' => User::getIdentidade('orgao_id'),
                        'status' => Status::STATUS_ATIVO
                    ])
                    ->all();

                if ($usersAuditors) {
                    Mail::sendMultiple(
                        './elaborar/auditor-publicacao',
                        null,
                        $usersAuditors,
                        'Plano de integridade finalizado'
                    );
                }

                $usersMonitoramento = User::find()
                    ->joinWith(['authAssignment'])
                    ->where([
                        'item_name' => User::PERFIL_MONITORAMENTO,
                        'orgao_id' => User::getIdentidade('orgao_id'),
                        'status' => Status::STATUS_ATIVO
                    ])
                    ->all();

                if ($usersMonitoramento) {
                    Mail::sendMultiple(
                        './elaborar/monitoramento-publicacao',
                        null,
                        $usersMonitoramento,
                        'Plano de integridade finalizado'
                    );
                } else {
                    Mail::sendMultiple(
                        './elaborar/auditor-monitoramento-publicacao',
                        null,
                        $usersAuditors,
                        'Plano de integridade finalizado'
                    );
                }

                $usersGrupoTrabalho = User::find()
                    ->joinWith(['authAssignment'])
                    ->where([
                        'item_name' => User::PERFIL_GRUPO_TRABALHO,
                        'orgao_id' => User::getIdentidade('orgao_id'),
                        'status' => Status::STATUS_ATIVO
                    ])
                    ->all();

                User::updateAll(
                    ['status' => Status::STATUS_INATIVO],
                    ['id' => ArrayHelper::getColumn($usersGrupoTrabalho, 'id')]
                );

                if ($usersGrupoTrabalho) {
                    Mail::sendMultiple(
                        './elaborar/grupo-trabalho-publicacao',
                        null,
                        $usersGrupoTrabalho,
                        'Plano de integridade finalizado'
                    );
                }

                if (User::getPerfil() === User::PERFIL_GRUPO_TRABALHO) {
                    Yii::$app->user->logout();
                    Universal::flash();
                    return $this->goHome();
                }

                Universal::flash();

                return $this->redirect(['@elaborar']);
            }
        }

        return $this->render('create', [
            'modelPublicacao' => $modelPublicacao,
        ]);
    }
}
