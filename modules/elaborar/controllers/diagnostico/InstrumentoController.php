<?php

namespace app\modules\elaborar\controllers\diagnostico;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\helpers\Universal;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\DiagnosticoInstrumento;
use app\modules\elaborar\models\DiagnosticoResultado;
use yii\base\ErrorException;

/**
 * InstrumentoController implements the CRUD actions for DiagnosticoInstrumento model.
 */
class InstrumentoController extends Controller
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
     * Cria um novo DiagnosticoInstrumento
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

        $modelDiagnosticoInstrumento = new DiagnosticoInstrumento();

        $modelDiagnosticoResultado = $modelDiagnostico->diagnosticoResultado;
        $modelDiagnosticoResultado = is_null($modelDiagnosticoResultado) ?
            new DiagnosticoResultado() :
            $modelDiagnosticoResultado;

        $request = Yii::$app->request;

        if ($modelDiagnosticoInstrumento->load($request->post()) && $modelDiagnosticoResultado->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($modelDiagnostico->page_key < 2) {
                    $modelDiagnostico->page_key = 2;

                    if (! ($flag = $modelDiagnostico->save())) {
                        throw new ErrorException('Falha ao processar solicitação.');
                    }
                }

                DiagnosticoInstrumento::deleteAll(['diagnostico_id' => $modelDiagnostico->id]);

                foreach ($modelDiagnosticoInstrumento->instrumentoIds as $instrumentoId) {
                    $newDiagnosticoInstrumento = new DiagnosticoInstrumento();
                    $newDiagnosticoInstrumento->instrumento_id = $instrumentoId;
                    $newDiagnosticoInstrumento->diagnostico_id = $modelDiagnostico->id;
                    if (! ($flag = $newDiagnosticoInstrumento->save(true, ['instrumento_id', 'diagnostico_id']))) {
                        $transaction->rollBack();
                        break;
                    }
                }

                $modelDiagnosticoResultado->diagnostico_id = $modelDiagnostico->id;

                if (! ($flag = $modelDiagnosticoResultado->save())) {
                    throw new ErrorException('Falha ao processar solicitação 2.');
                }

                if ($flag) {
                    Universal::flash();
                    $transaction->commit();
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
