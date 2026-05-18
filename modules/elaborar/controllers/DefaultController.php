<?php

namespace app\modules\elaborar\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\User;
use app\models\PlanoIntegridade;
use app\models\Status;
use Yii;

/**
 * Default controller for the `elaborar` module
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
     * Página inicial do módulo elaborar
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('modulo-elaboracao')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $userOrgaoId = User::getIdentidade('orgao_id');

        $planoExists = PlanoIntegridade::find()
            ->where(['in', 'status', [Status::PLANO_ELABORACAO, Status::PLANO_N_INICIADO, Status::PLANO_PUBLICADO]])
            ->andWhere(['orgao_id' => $userOrgaoId])
            ->one();

        $isObservador = User::getPerfil() === User::PERFIL_OBSERVADOR;

        if (!$planoExists && $isObservador) {
            Universal::flash('error', 'Não foi encontrado nenhum programa de integridade.');
            return $this->redirect(['/site/index']);
        } elseif (!$planoExists) {
            $modelPlano = new PlanoIntegridade();
            $modelPlano->edicao = '1° Edição';
            $modelPlano->status = Status::PLANO_N_INICIADO;
            $modelPlano->versao = 1;
            $modelPlano->orgao_id = $userOrgaoId;

            $modelPlano->save();
        }

        $modelPlano = $planoExists ?? $modelPlano;

        $hasDefaultModulePermission = Universal::temPermissao('preencher-elaboracao', $modelPlano);

        $preparePermissions = [
            'hasDefault' => $hasDefaultModulePermission ? '' : 'disabled',
            'hasValidacao' => $hasDefaultModulePermission && is_null($modelPlano->plano_integridade_referencia_id) ?
                '' :
                'disabled',
            'hasPublicacao' => $hasDefaultModulePermission ? '' : 'disabled',
            'hasIntegridade' => Universal::temPermissao('plano-integridade-elaborar', $modelPlano) ? '' : 'disabled',
            'hasAcao' => Universal::temPermissao('plano-acao-elaborar', $modelPlano) &&
            (!isset($modelPlano->publicacao) || !is_null($modelPlano->publicacao->plano_acao_arquivo)) ?
                '' :
                'disabled',
            'hasAcaoFile' => $modelPlano->publicacao && !is_null($modelPlano->publicacao->plano_acao_arquivo),
        ];

        return $this->render('index', [
            'modelPlano' => $modelPlano,
            'preparePermissions' => $preparePermissions
        ]);
    }
}
