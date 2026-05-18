<?php

namespace app\modules\avaliar\controllers\relatorio;

use app\components\helpers\Universal;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\Acao;
use app\models\pesquisa\HistoricoSearch;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\models\User;
use app\modules\elaborar\models\Eixo;

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
     * Página inicial dos relátorios
     * @return mixed
     */
    public function actionIndex()
    {
        $userOrgaoId = User::getIdentidade('orgao_id');

        $modelPlano = PlanoIntegridade::findOne([
            'orgao_id' => $userOrgaoId,
            'status' => Status::PLANO_PUBLICADO
        ]);

        if (!Universal::temPermissao('preencher-avaliacao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

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

        $searchModelHistorico = new HistoricoSearch();

        return $this->render('index', [
            'searchModelHistorico' => $searchModelHistorico,
            'optionsAcao' => $optionsAcao
        ]);
    }
}
