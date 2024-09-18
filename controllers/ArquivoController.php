<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\components\helpers\Universal;
use app\models\Arquivo;
use app\modules\executar\models\AcaoExecucaoArquivo;

class ArquivoController extends \yii\web\Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Deletes an existing Arquivo model.
     * If deletion is successful, the browser will be redirected to the 'previous' page.
     * @param integer $id Número identificador do arquivo
     *
     * @return mixed
     */
    public function actionDelete($id, $execucaoArquivoId = null)
    {
        if (!Yii::$app->user->isGuest) {
            if (!is_null($execucaoArquivoId)) {
                $modelArquivo = Arquivo::findOne($id);

                $countUseSameFile = $modelArquivo->getAcaoExecucaoArquivos()->count();

                if ($countUseSameFile > 1) {
                    $modelAcaoExecucaoArquivo = AcaoExecucaoArquivo::findOne($execucaoArquivoId);

                    if ($modelAcaoExecucaoArquivo->delete()) {
                        Universal::flash();
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }

            if (Universal::deleteArquivo($id)) {
                Universal::flash();
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->redirect(['/site']);
    }
}
