<?php

namespace app\modules\monitorar\controllers\relatorio;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\pesquisa\HistoricoSearch;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\elaborar\models\Eixo;

/**
 * Default controller for the `monitorar` module action `gerar relatorio`
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
     * Renderiza a index para iniciar o processo de gerar relatorios
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        $isObservador = ArrayHelper::isIn(
            User::getPerfil(),
            [User::PERFIL_OBSERVADOR, User::PERFIL_ALTA_ADMINISTRACAO]
        );

        $modelsEixo = Eixo::findAll(['plano_integridade_id' => $modelPlano->id]);

        $eixoIds = ArrayHelper::getColumn($modelsEixo, 'id');

        $optionsAcao = ArrayHelper::map(
            Acao::find()
                ->where(['in', 'eixo_id', $eixoIds])
                ->all(),
            'id',
            function ($acao) {
                return "{$acao->numero} - {$acao->titulo}";
            }
        );

        if (!Universal::temPermissao('preencher-monitoramento', $modelPlano) || $isObservador) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModelHistorico = new HistoricoSearch();

        return $this->render('index', [
            'searchModelHistorico' => $searchModelHistorico,
            'optionsAcao' => $optionsAcao
        ]);
    }
}
