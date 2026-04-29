<?php

namespace app\modules\elaborar\controllers\diagnostico;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\models\Status;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\DiagnosticoInfoEstrategica;

/**
 * InfoEstrategicaController implements the CRUD actions for DiagnosticoInfoEstrategica model.
 */
class InfoEstrategicaController extends Controller
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
     * Cria um novo DiagnosticoInfoEstrategica
     * Se a criação for um sucesso,o browser te redirecionará para a pagina 'update'
     *
     * @param integer $diagnosticoId Número identificador do diagnostico.
     * @return mixed
     */
    public function actionCreate($diagnosticoId)
    {
        $modelDiagnostico = Diagnostico::findOne($diagnosticoId);
        $modelPlano = $modelDiagnostico->planoIntegridade;

        $hasPermission = !is_null($modelDiagnostico) &&
            Universal::temPermissao('preencher-elaboracao', $modelPlano);

        if (!$hasPermission) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelDiagnosticoInfoEstrategica = $modelDiagnostico->diagnosticoInfoEstrategica;

        $modelDiagnosticoInfoEstrategica = is_null($modelDiagnostico->diagnosticoInfoEstrategica) ?
            new DiagnosticoInfoEstrategica() :
            $modelDiagnosticoInfoEstrategica;

        if ($modelDiagnosticoInfoEstrategica->load(Yii::$app->request->post())) {
            $modelPlano->status = Status::PLANO_ELABORACAO;

            if ($modelDiagnostico->page_key < 1) {
                $modelDiagnostico->page_key = 1;
                $modelDiagnostico->save();
            }

            $modelDiagnosticoInfoEstrategica->diagnostico_id = $modelDiagnostico->id;

            if ($modelDiagnosticoInfoEstrategica->save() && $modelPlano->save()) {
                Universal::flash();

                return $this->redirect([
                    '@elaborar/diagnostico/default/update',
                    'planoId' => $modelDiagnostico->plano_integridade_id
                ]);
            }
        }
    }
}
