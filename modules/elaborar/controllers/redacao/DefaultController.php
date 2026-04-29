<?php

namespace app\modules\elaborar\controllers\redacao;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\modules\elaborar\models\Eixo;
use app\modules\elaborar\models\Subeixo;
use app\models\Acao;
use app\models\User;
use app\modules\elaborar\models\pesquisa\EixoSearch;
use app\modules\elaborar\models\pesquisa\SubeixoSearch;
use app\models\pesquisa\AcaoSearch;

class DefaultController extends \yii\web\Controller
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
     * Cria um novo Eixo/Subeixo/Acao
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @param string $key Identificador do registro a ser atualizado eixo|subeixo|acao
     * @param integer $updateId Número identificador do eixo|subeixo|acao
     * @return mixed
     */
    public function actionCreate($planoId, $key = '', $updateId = '')
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('preencher-elaboracao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelEixo = $key == 'eixo' && !empty($updateId) ? Eixo::findOne($updateId) : new Eixo();
        $modelSubeixo = $key == 'subeixo' && !empty($updateId) ? Subeixo::findOne($updateId) : new Subeixo();
        $modelAcao = $key == 'acao' && !empty($updateId) ? Acao::findOne($updateId) : new Acao();

        $eixoExists = Eixo::find()->count();

        if (!empty($updateId)) {
            switch ($key) {
                case 'eixo':
                    $orgaoElementUpdate = $modelEixo->planoIntegridade->orgao_id;
                    break;
                case 'subeixo':
                    $orgaoElementUpdate = $modelSubeixo->eixo->planoIntegridade->orgao_id;
                    break;
                case 'acao':
                    $orgaoElementUpdate = $modelAcao->eixo->planoIntegridade->orgao_id;
                    break;
            }
        }

        $addClassDisabled = $eixoExists ? '' : 'disabled';

        $prepareTabs = [
            'eixo' => empty($key) || $key == 'eixo' ? 'nav-link active' : 'nav-link',
            'subeixo' => $key == 'subeixo' ? "nav-link active" : "nav-link {$addClassDisabled}",
            'acao' => $key == 'acao' ? 'nav-link active' : "nav-link {$addClassDisabled}",
        ];

        if (isset($orgaoElementUpdate) && $orgaoElementUpdate != User::getIdentidade(('orgao_id'))) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $searchModelEixo = new EixoSearch();
        $searchModelEixo->plano_integridade_id = $planoId;
        $dataProviderEixo = $searchModelEixo->search(Yii::$app->request->queryParams);

        $searchModelSubeixo = new SubeixoSearch();
        $searchModelSubeixo->plano_integridade_id = $planoId;
        $dataProviderSubeixo = $searchModelSubeixo->search(Yii::$app->request->queryParams);

        $searchModelAcao = new AcaoSearch();
        $searchModelAcao->plano_integridade_id = $planoId;
        $dataProviderAcao = $searchModelAcao->search(Yii::$app->request->queryParams);

        if ($key == 'acao' && !is_null($updateId)) {
            $modelAcao->unidadeApoioIds = ArrayHelper::getColumn(
                $modelAcao->acaoUnidadeApoios,
                'unidade_administrativa_id'
            );
        }

        $optionsEixo = ArrayHelper::map(
            Eixo::find()
                ->where(['plano_integridade_id' => $planoId])
                ->orderBy(['titulo' => 'SORT_ASC'])
                ->all(),
            'id',
            'titulo'
        );

        return $this->render('create', [
            'planoId' => $modelPlano->id,
            'modelEixo' => $modelEixo,
            'searchModelEixo' => $searchModelEixo,
            'dataProviderEixo' => $dataProviderEixo,
            'modelSubeixo' => $modelSubeixo,
            'searchModelSubeixo' => $searchModelSubeixo,
            'dataProviderSubeixo' => $dataProviderSubeixo,
            'modelAcao' => $modelAcao,
            'searchModelAcao' => $searchModelAcao,
            'dataProviderAcao' => $dataProviderAcao,
            'prepareTabs' => $prepareTabs,
            'optionsEixo' => $optionsEixo
        ]);
    }
}
