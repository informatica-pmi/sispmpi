<?php

namespace app\modules\executar\controllers\acao;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\helpers\Universal;
use app\models\Acao;
use app\models\Status;
use app\modules\executar\models\AcaoTipo;

/**
 * InformacaoController implements the CRUD actions for Eixo model.
 */
class InformacaoController extends Controller
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
     * Atualiza uma Acao existente
     * Se a atualizacao for um sucesso, o browser te redirecionará para a página 'acao/view'
     *
     * @param integer $acaoId Número identificador da ação
     * @return mixed
     */
    public function actionUpdate($acaoId)
    {
        $modelAcao = Acao::findOne($acaoId);

        $modelAcao->saveAudit = Status::STATUS_SIM;

        $modelPlano = $modelAcao->eixo->planoIntegridade;

        if (!Universal::temPermissao('preencher-execucao', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        if ($modelAcao->load(Yii::$app->request->post())) {
            if ($modelAcao->classificacao === Acao::CLASSIFICACAO_ACAO_CONTINUA) {
                $modelAcao->previsao_conclusao = '';
            }

            if ($modelAcao->save()) {
                $oldAcaoTiposIds = ArrayHelper::getColumn(
                    AcaoTipo::findAll(['acao_id' => $modelAcao->id]),
                    'id'
                );

                if (!empty($oldAcaoTiposIds)) {
                    AcaoTipo::deleteAll(['id' => $oldAcaoTiposIds]);
                }

                foreach ($modelAcao->tipoIds as $tipo) {
                    $newAcaoTipo = new AcaoTipo();
                    $newAcaoTipo->tipo_id = $tipo;
                    $newAcaoTipo->acao_id = $modelAcao->id;

                    $newAcaoTipo->save();
                }

                Universal::flash();
                $this->redirect(['@executar/acao/default/view', 'acaoId' => $modelAcao->id]);
            }
        }
    }
}
