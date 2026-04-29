<?php

namespace app\modules\auditor\controllers;

use app\components\helpers\Universal;
use app\models\pesquisa\PlanoIntegridadeSearch;
use app\models\PlanoIntegridade;
use app\models\User;
use yii\data\Pagination;
use yii\web\Controller;

/**
 * Default controller for the `auditor` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (!Universal::temPermissao('modulo-auditor')) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $userOrgaoId = User::getIdentidade('orgao_id');

        $searchModel = new PlanoIntegridadeSearch();

        $searchModel->orgao_id = $userOrgaoId;

        $dataProvider = $searchModel->search([]);

        $modelsPlano = $dataProvider->getModels();

        unset($modelsPlano[array_key_last($modelsPlano)]);

        $pagination = new Pagination([
            'pageSize' => 10,
            'totalCount' => count($modelsPlano),
        ]);

        return $this->render('index', [
            'modelsPlano' => $modelsPlano,
            'pagination' => $pagination,
        ]);
    }
}
