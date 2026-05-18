<?php

namespace app\modules\elaborar\controllers\diagnostico;

use app\modules\elaborar\models\DiagnosticoEixoTematico;
use Yii;
use yii\base\ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\components\helpers\Mail;
use app\components\helpers\Universal;
use app\models\Status;
use app\models\User;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\DiagnosticoResultado;

/**
 * ResultadoController implements the CRUD actions for DiagnosticoResultado model.
 */
class ResultadoController extends Controller
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
     * Cria um novo DiagnosticoResultado
     * Se a criação for um sucesso,o browser te redirecionará para a pagina 'update'
     *
     * @param integer $diagnosticoId Número identificador do diagnostico
     * @return mixed
     */
    public function actionCreate($diagnosticoId)
    {
        $modelDiagnostico = Diagnostico::findOne($diagnosticoId);

        $hasPermission = !is_null($modelDiagnostico) &&
            Universal::temPermissao('preencher-elaboracao', $modelDiagnostico->planoIntegridade);

        if (!$hasPermission) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelDiagnosticoResultadoPrograma = $modelDiagnostico->diagnosticoResultado;
        $modelDiagnosticoResultadoPrograma->scenario = DiagnosticoResultado::SCENARIO_UPDATE_PROGRAMA;

        if ($modelDiagnosticoResultadoPrograma->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $modelDiagnosticoResultadoPrograma->diagnostico_id = $modelDiagnostico->id;

                if ($flag = $modelDiagnosticoResultadoPrograma->save()) {
                    DiagnosticoEixoTematico::deleteAll(['diagnostico_id' => $modelDiagnostico->id]);

                    foreach ($modelDiagnosticoResultadoPrograma->eixoTematicoIds as $eixoTematicoId) {
                        $newDiagnosticoEixoTematico = new DiagnosticoEixoTematico();
                        $newDiagnosticoEixoTematico->eixo_tematico_id = $eixoTematicoId;
                        $newDiagnosticoEixoTematico->diagnostico_id = $modelDiagnostico->id;

                        if (! ($flag = $newDiagnosticoEixoTematico->save())) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }

                if ($flag) {
                    Universal::flash();
                    $transaction->commit();
                    return $this->redirect(['@elaborar']);
                }
            } catch (ErrorException $e) {
                Universal::flash('error', $e->getMessage());
                $transaction->rollBack();
            }

            return $this->redirect([
                '@elaborar/diagnostico/default/update',
                'planoId' => $modelDiagnostico->plano_integridade_id
            ]);
        }
    }
}
