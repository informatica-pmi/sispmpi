<?php

namespace app\modules\elaborar\controllers;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use app\components\helpers\Universal;
use app\models\User;
use app\models\Acao;
use app\models\PlanoIntegridade;
use app\modules\elaborar\models\Subeixo;

/**
 * Gerar Plano de ação parcial em .xlsx
 */
class PlanoAcaoParcialController extends Controller
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
     * Gera o plano de integridade parcial em extensão xlsx
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionIndex($planoId)
    {
        $labelNull = 'Não se aplica/Não definido';

        $modelPlano = PlanoIntegridade::findOne($planoId);

        if (!Universal::temPermissao('plano-acao-elaborar', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        $modelsEixo = $modelPlano->eixos;

        $modelsSubeixo = Subeixo::findAll(['eixo_id' => ArrayHelper::getColumn($modelsEixo, 'id')]);

        $modelsAcao = Acao::findAll(['eixo_id' => ArrayHelper::getColumn($modelsEixo, 'id')]);

        $wizard = new Html();
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator(User::getIdentidade('nome'));

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'ffffff'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => '999999'],
            ],
        ];

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Titulo')
            ->setCellValue('B1', 'Descrição');

        $indexEixo = 2;
        foreach ($modelsEixo as $modelEixo) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A{$indexEixo}", $modelEixo->titulo)
                ->setCellValue("B{$indexEixo}", $wizard->toRichTextObject($modelEixo->descricao));

            $indexEixo += 1;
        }

        $spreadsheet->getActiveSheet(0)
            ->getStyle("B2:B{$indexEixo}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_FILL);

        $spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setWidth(30);
        $spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet(0)->getStyle('A1:B1')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet(0)->setTitle('Eixos');

        /* new worksheet */
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1)
            ->setCellValue('A1', 'Eixo')
            ->setCellValue('B1', 'Titulo')
            ->setCellValue('C1', 'Descrição');

        $indexSubeixo = 2;
        foreach ($modelsSubeixo as $modelSubeixo) {
            $spreadsheet->setActiveSheetIndex(1)
                ->setCellValue("A{$indexSubeixo}", $modelSubeixo->eixo->titulo)
                ->setCellValue("B{$indexSubeixo}", $modelSubeixo->titulo)
                ->setCellValue("C{$indexSubeixo}", $wizard->toRichTextObject($modelSubeixo->descricao));

            $indexSubeixo += 1;
        }

        $rangeSubeixo = range('A', 'C');
        foreach ($rangeSubeixo as $letter) {
            $spreadsheet->getActiveSheet(1)->getColumnDimension($letter)->setWidth(30);
        }

        $spreadsheet->getActiveSheet(1)
            ->getStyle("C2:C{$indexSubeixo}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_FILL);

        $spreadsheet->getActiveSheet(1)->getStyle('A1:C1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet(1)->setTitle('Subeixos');

        /* new worksheet */
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2)
            ->setCellValue('A1', 'Eixo')
            ->setCellValue('B1', 'Subeixo')
            ->setCellValue('C1', 'Número da ação')
            ->setCellValue('D1', 'Título')
            ->setCellValue('E1', 'Descrição')
            ->setCellValue('F1', 'Unidade administrativa executora')
            ->setCellValue('G1', 'Objetivos da ação')
            ->setCellValue('H1', 'Benefícios para a instituição')
            ->setCellValue('I1', 'Unidade administrativa de apoio');

        $indexAcao = 2;
        foreach ($modelsAcao as $modelAcao) {
            $unidadeApoio = implode(", ", ArrayHelper::getColumn(
                $modelAcao->acaoUnidadeApoios,
                'unidadeAdministrativa.nome'
            ));

            $spreadsheet->setActiveSheetIndex(2)
                ->setCellValue("A{$indexAcao}", $modelAcao->eixo->titulo)
                ->setCellValue("B{$indexAcao}", $modelAcao->subeixo_id ?
                    $modelAcao->subeixo->titulo :
                    $labelNull)
                ->setCellValue("C{$indexAcao}", $modelAcao->numero)
                ->setCellValue("D{$indexAcao}", $modelAcao->titulo)
                ->setCellValue("E{$indexAcao}", $wizard->toRichTextObject($modelAcao->descricao))
                ->setCellValue("F{$indexAcao}", $modelAcao->unidadeExecutora->nome)
                ->setCellValue("G{$indexAcao}", $modelAcao->objetivo ?
                    $wizard->toRichTextObject($modelAcao->objetivo) :
                    $labelNull)
                ->setCellValue("H{$indexAcao}", $modelAcao->beneficio_instituicao ?
                    $wizard->toRichTextObject($modelAcao->beneficio_instituicao) :
                    $labelNull)
                ->setCellValue("I{$indexAcao}", $modelAcao->acaoUnidadeApoios ? $unidadeApoio : $labelNull);

            if (strlen($unidadeApoio) > 35) {
                $spreadsheet->getActiveSheet(1)
                    ->getStyle("I{$indexAcao}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_FILL);
            }


            $indexAcao += 1;
        }

        $rangeAcao = range('A', 'I');
        foreach ($rangeAcao as $letter) {
            $spreadsheet->getActiveSheet(2)->getColumnDimension($letter)->setWidth(30);
        }
        $spreadsheet->getActiveSheet(2)->getStyle('A1:I1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet(2)->setTitle('Ações');

        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="plano_acao_parcial.xlsx"');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
