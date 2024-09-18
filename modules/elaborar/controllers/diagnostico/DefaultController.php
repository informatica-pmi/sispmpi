<?php

namespace app\modules\elaborar\controllers\diagnostico;

use app\models\EixoTematico;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\helpers\OrganSpecific;
use app\components\helpers\TabsHeader;
use app\components\helpers\Universal;
use app\models\Instrumento;
use app\models\PlanoIntegridade;
use app\modules\elaborar\models\Diagnostico;
use app\modules\elaborar\models\DiagnosticoInfoEstrategica;
use app\modules\elaborar\models\DiagnosticoInstrumento;
use app\modules\elaborar\models\DiagnosticoResultado;

/**
 * DefaultController implements the CRUD actions for Diagnostico model.
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
     * Cria um novo Diagnostico
     * Se a criação for um sucesso, o browser te redirecionará para a página 'create'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionCreate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        $hasDefaultModulePermission = Universal::temPermissao('preencher-elaboracao', $modelPlano);

        if (!$hasDefaultModulePermission) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $diagnosticoExists = Diagnostico::findOne(['plano_integridade_id' => $planoId]);

        if (!$diagnosticoExists) {
            $newDiagnostico = new Diagnostico();
            $newDiagnostico->plano_integridade_id = $planoId;
            $newDiagnostico->page_key = 0;
            $newDiagnostico->save();
        }

        $modelDiagnostico = $diagnosticoExists ?? $newDiagnostico;

        $modelDiagnosticoInfoEstrategica = new DiagnosticoInfoEstrategica();

        $prepareTabs = TabsHeader::generateDiagnostico($hasDefaultModulePermission);

        return $this->render('create', [
            'modelDiagnostico' => $modelDiagnostico,
            'modelDiagnosticoInfoEstrategica' => $modelDiagnosticoInfoEstrategica,
            'prepareTabs' => $prepareTabs,
        ]);
    }

    /**
     * Atualiza um Diagnostico existente
     * Se a atualização for um sucesso, o browser te redirecionará para a pagina 'update'
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        $hasDefaultModulePermission = Universal::temPermissao('preencher-elaboracao', $modelPlano);

        if (!$hasDefaultModulePermission) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelDiagnostico = $modelPlano->diagnostico;

        $modelDiagnosticoInfoEstrategica = $modelDiagnostico->diagnosticoInfoEstrategica ??
            new DiagnosticoInfoEstrategica();

        $modelDiagnosticoInstrumento = new DiagnosticoInstrumento();

        $modelsInstrumento = [new Instrumento()];

        $oldInstrumentos = $modelDiagnostico->diagnosticoInstrumentos;

        $modelDiagnosticoInstrumento->instrumentoIds = empty($oldInstrumentos) ?
            [] :
            ArrayHelper::getColumn($oldInstrumentos, 'instrumento_id');

        $optionsInstrumento = OrganSpecific::search(Instrumento::className());

        if (empty($modelDiagnostico->diagnosticoResultado)) {
            $modelDiagnosticoResultado = new DiagnosticoResultado();
            $modelDiagnosticoResultadoPrograma = new DiagnosticoResultado();
        } else {
            $modelDiagnosticoResultado = $modelDiagnostico->getDiagnosticoResultado()->one();
            $modelDiagnosticoResultadoPrograma = $modelDiagnostico->getDiagnosticoResultado()->one();
        }

        $modelDiagnosticoResultadoPrograma->scenario = DiagnosticoResultado::SCENARIO_UPDATE_PROGRAMA;

        $oldEixosTematicos = $modelDiagnostico->diagnosticoEixoTematicos;

        $modelDiagnosticoResultadoPrograma->eixoTematicoIds = empty($oldEixosTematicos) ?
            [] :
            ArrayHelper::getColumn($oldEixosTematicos, 'eixo_tematico_id');

        $optionsEixoTematico = OrganSpecific::search(EixoTematico::className());

        $prepareTabs = TabsHeader::generateDiagnostico($hasDefaultModulePermission);

        return $this->render('update', [
            'modelDiagnostico' => $modelDiagnostico,
            'modelDiagnosticoInfoEstrategica' => $modelDiagnosticoInfoEstrategica,
            'modelDiagnosticoResultado' => $modelDiagnosticoResultado,
            'modelDiagnosticoResultadoPrograma' => $modelDiagnosticoResultadoPrograma,
            'modelDiagnosticoInstrumento' => $modelDiagnosticoInstrumento,
            'modelsInstrumento' => (empty($modelsInstrumento)) ? [new Instrumento()] : $modelsInstrumento,
            'optionsInstrumento' => $optionsInstrumento,
            'optionsEixoTematico' => $optionsEixoTematico,
            'prepareTabs' => $prepareTabs
        ]);
    }
}
