<?php

namespace app\modules\elaborar\controllers;

use app\components\helpers\Universal;
use app\models\PlanoIntegridade;
use app\models\Status;
use app\modules\elaborar\models\GrupoInstituido;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Border;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Language;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Gerar plano de integridade parcial em .doc
 */
class PlanoIntegridadeParcialController extends Controller
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
     * Gera o plano de integridade em extensão doc
     *
     * @param integer $planoId Número identificador do plano de integridade
     * @return mixed
     */
    public function actionIndex($planoId)
    {
        $modelPlano = PlanoIntegridade::findOne($planoId);

        Settings::setOutputEscapingEnabled(true);

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::PT_BR));
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(12);

        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'color' => '65a47b']);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14, 'color' => '65a47b']);

        $fontStyleLabel = 'fontStyleLabel';
        $phpWord->addFontStyle($fontStyleLabel, ['bold' => true]);

        $fontStyleLink = 'fontStyleLink';
        $phpWord->addFontStyle($fontStyleLink, ['underline' => Font::UNDERLINE_SINGLE]);

        $section = $phpWord->addSection([
            'vAlign' => VerticalJc::CENTER,
            'borderColor' => '65a47b',
            'borderSize' => 24
        ]);
        $header = $section->addHeader();
        $header->addImage(
            Yii::getAlias('@webroot/images/logo-sidebar.png'),
            ['width' => 40, 'height' => 30, 'alignment' => Jc::END]
        );

        $section->addText(
            'Programa de integridade - Versão ' . number_format($modelPlano->versao, 2, '.', ''),
            ['allCaps' => true, 'bold' => true, 'size' => 20, 'name' => 'Bahnschrift'],
            ['alignment' => Jc::CENTER]
        );
        $section->addText(
            $modelPlano->orgao->nome,
            ['size' => 16, 'color' => 'A6A6A6', 'name' => 'Bahnschrift'],
            ['alignment' => Jc::CENTER]
        );

        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $footer->addPreserveText('Página {PAGE} de {NUMPAGES}', null, ['alignment' => Jc::CENTER]);

        if ($modelPlano->grupo) {
            $modelGrupo = $modelPlano->grupo;
            $firstGrupoInstituido = $modelGrupo->firstGrupoInstituido;
            $extensionsGrupo = $modelGrupo->getGrupoInstituidos()
                ->where(['!=', 'order', 0])
                ->andWhere(['tipo' => GrupoInstituido::TIPO_GRUPO])
                ->all();

            $section->addTitle('Instituição da comissão de integridade');

            if ($firstGrupoInstituido->formalmente === Status::STATUS_NAO) {
                $section->addText($firstGrupoInstituido->getAttributeLabel('data_inicio') . ': ' .
                    Universal::convertDate($firstGrupoInstituido->data_inicio));
            } else {
                $section->addText($firstGrupoInstituido->getAttributeLabel('nome_numero'), $fontStyleLabel);
                $section->addText($firstGrupoInstituido->nome_numero);

                $section->addText($firstGrupoInstituido->getAttributeLabel('data_publicacao'), $fontStyleLabel);
                $section->addText(Universal::convertDate($firstGrupoInstituido->data_publicacao));

                $section->addText($firstGrupoInstituido->getAttributeLabel('data_prevista_conclusao'), $fontStyleLabel);
                $section->addText(Universal::convertDate($firstGrupoInstituido->data_prevista_conclusao));

                $section->addText($firstGrupoInstituido->getAttributeLabel('link'), $fontStyleLabel);
                $section->addLink($firstGrupoInstituido->link, 'Acessar', $fontStyleLink);
            }

            if ($extensionsGrupo) {
                foreach ($extensionsGrupo as $extensionGrupo) {
                    $extensionGrupo->scenario = GrupoInstituido::SCENARIO_ADITIONAL;

                    $section->addTitle("Prorrogação do Prazo #{$extensionGrupo->order}", 2);

                    $section->addText($extensionGrupo->getAttributeLabel('nome_numero'), $fontStyleLabel);
                    $section->addText($extensionGrupo->nome_numero);

                    $section->addText($extensionGrupo->getAttributeLabel('data_publicacao'), $fontStyleLabel);
                    $section->addText(Universal::convertDate($extensionGrupo->data_publicacao));

                    $section->addText($extensionGrupo->getAttributeLabel('data_prevista_conclusao'), $fontStyleLabel);
                    $section->addText(Universal::convertDate($extensionGrupo->data_prevista_conclusao));

                    $section->addText($extensionGrupo->getAttributeLabel('link'), $fontStyleLabel);
                    $section->addLink($extensionGrupo->link, 'Acessar', $fontStyleLink);
                }
            }

            $section->addTitle('Servidores');

            $firstGrupoServidors = $modelGrupo->firstGrupoServidors;
            $alterationGrupoServidors = $modelGrupo->withoutFirstGrupoServidors;
            $orders = array_values(array_unique(ArrayHelper::getColumn($alterationGrupoServidors, 'order')));
            $tableServidors = $section->addTable();

            foreach ($firstGrupoServidors as $firstGrupoServidor) {
                $tableServidors->addRow();
                $isBold = $firstGrupoServidor->coordenador === Status::STATUS_SIM;

                $tableServidors->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])->addText(
                    "{$firstGrupoServidor->servidor->nome}, " .
                    "{$firstGrupoServidor->servidor->masp_matricula} - " .
                    "{$firstGrupoServidor->servidor->unidadeAdministrativa->nome}",
                    ['bold' => $isBold]
                );
            }

            $section->addTextBreak();

            if ($alterationGrupoServidors) {
                foreach ($orders as $order) {
                    $section->addTitle("Alteração na composição da comissão de integridade #{$order}", 2);

                    $extensionServidor = $modelGrupo->getGrupoInstituidos()
                        ->where(['tipo' => GrupoInstituido::TIPO_SERVIDOR, 'order' => $order])
                        ->one();

                    if ($extensionServidor) {
                        $extensionServidor->scenario = GrupoInstituido::SCENARIO_ADITIONAL_SERVIDOR;

                        $section->addText($extensionServidor->getAttributeLabel('nome_numero'), $fontStyleLabel);
                        $section->addText($extensionServidor->nome_numero);

                        $section->addText($extensionServidor->getAttributeLabel('data_publicacao'), $fontStyleLabel);
                        $section->addText(Universal::convertDate($extensionServidor->data_publicacao));

                        $section->addText($extensionServidor->getAttributeLabel('link'), $fontStyleLabel);
                        $section->addLink($extensionServidor->link, 'Acessar', $fontStyleLink);

                        $section->addTextBreak();
                    }

                    $table = $section->addTable();

                    foreach ($alterationGrupoServidors as $alterationGrupoServidor) {
                        $isBold = false;
                        $isDoubleStrikethrough = false;

                        if ($alterationGrupoServidor->order == $order) {
                            $table->addRow();

                            if ($alterationGrupoServidor->status == Status::STATUS_INATIVO) {
                                $isDoubleStrikethrough = true;
                            } elseif ($alterationGrupoServidor->coordenador === Status::STATUS_SIM) {
                                $isBold = true;
                            }

                            $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])->addText(
                                "{$alterationGrupoServidor->servidor->nome}, " .
                                "{$alterationGrupoServidor->servidor->masp_matricula} - " .
                                "{$alterationGrupoServidor->servidor->unidadeAdministrativa->nome}",
                                ['bold' => $isBold, 'doubleStrikethrough' => $isDoubleStrikethrough]
                            );
                        }
                    }

                    $section->addTextBreak();
                }
            }
        }

        if ($modelPlano->diagnostico) {
            $modelDiagnostico = $modelPlano->diagnostico;

            if ($modelDiagnostico->diagnosticoInfoEstrategica) {
                $modelInfoEstrategica = $modelDiagnostico->diagnosticoInfoEstrategica;

                $section->addTitle('Programa de integridade');
                $section->addTitle('Estrutura organizacional', 2);

                $section->addText($modelInfoEstrategica->getAttributeLabel('missao'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->missao),
                    false,
                    false
                );

                $section->addText($modelInfoEstrategica->getAttributeLabel('visao'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->visao),
                    false,
                    false
                );

                $section->addText($modelInfoEstrategica->getAttributeLabel('valores'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->valores),
                    false,
                    false
                );

                $section->addText($modelInfoEstrategica->getAttributeLabel('estrutura_organica'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->estrutura_organica),
                    false,
                    false
                );

                $section->addText($modelInfoEstrategica->getAttributeLabel('competencias'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->competencias),
                    false,
                    false
                );

                $section->addText($modelInfoEstrategica->getAttributeLabel('atribuicoes'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelInfoEstrategica->atribuicoes),
                    false,
                    false
                );
            }

            if ($modelDiagnostico->diagnosticoInstrumentos) {
                $modelDiagnosticoInstrumentos = $modelDiagnostico->diagnosticoInstrumentos;
                $modelDiagnosticoResultado = $modelDiagnostico->diagnosticoResultado;

                $section->addTitle('Diagnóstico do ambiente de integridade', 2);

                $section->addText(
                    $modelDiagnosticoInstrumentos[0]->getAttributeLabel('instrumentoIds'),
                    $fontStyleLabel
                );

                $table = $section->addTable();
                foreach ($modelDiagnosticoInstrumentos as $diagnosticoInstrumento) {
                    $instrumento = $diagnosticoInstrumento->instrumento;

                    $table->addRow();

                    $isBold = !is_null($instrumento->orgao_id);

                    $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])
                        ->addText($instrumento->nome, ['bold' => $isBold]);
                }

                $section->addTextBreak();

                $section->addText($modelDiagnosticoResultado->getAttributeLabel('descricao'), $fontStyleLabel);
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->descricao),
                    false,
                    false
                );
            }

            if ($modelDiagnostico->diagnosticoResultado) {
                $modelDiagnosticoResultado = $modelDiagnostico->diagnosticoResultado;
                $modelsDiagnosticoEixoTematico = $modelDiagnostico->diagnosticoEixoTematicos;

                $section->addTitle('Programa de integridade', 2);

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('objetivos_trabalhados'),
                    $fontStyleLabel
                );
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->objetivos_trabalhados),
                    false,
                    false
                );

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('objetivos_estrategicos'),
                    $fontStyleLabel
                );
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->objetivos_estrategicos),
                    false,
                    false
                );

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('estrutura_governanca'),
                    $fontStyleLabel
                );
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->estrutura_governanca),
                    false,
                    false
                );

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('periodicidade_monitoramentos'),
                    $fontStyleLabel
                );
                $section->addText($modelDiagnosticoResultado->periodicidade_monitoramentos);

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('periodicidade_avaliacoes'),
                    $fontStyleLabel
                );
                $section->addText($modelDiagnosticoResultado->periodicidade_avaliacoes);

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('periodicidade_atualizacoes'),
                    $fontStyleLabel
                );
                $section->addText($modelDiagnosticoResultado->periodicidade_atualizacoes);

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('aspectos_comunicacao'),
                    $fontStyleLabel
                );
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->aspectos_comunicacao),
                    false,
                    false
                );

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('aspectos_capacitacao'),
                    $fontStyleLabel
                );
                Html::addHtml(
                    $section,
                    Universal::stripTags($modelDiagnosticoResultado->aspectos_capacitacao),
                    false,
                    false
                );

                $section->addText(
                    $modelDiagnosticoResultado->getAttributeLabel('eixoTematicoIds'),
                    $fontStyleLabel
                );

                $table = $section->addTable();
                foreach ($modelsDiagnosticoEixoTematico as $diagnosticoEixoTematico) {
                    $eixoTematico = $diagnosticoEixoTematico->eixoTematico;

                    $table->addRow();

                    $isBold = !is_null($eixoTematico->orgao_id);

                    $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])
                        ->addText($eixoTematico->nome, ['bold' => $isBold]);
                }

                $section->addTextBreak();
            }
        }

        if ($modelPlano->eixos) {
            $modelsEixo = $modelPlano->eixos;

            $section->addTitle('Plano de integridade');

            foreach ($modelsEixo as $eixo) {
                $section->addTitle($eixo->titulo, 2);

                Html::addHtml($section, Universal::stripTags($eixo->descricao), false, false);

                if ($eixo->acaos) {
                    foreach ($eixo->acaos as $acao) {
                        if (is_null($acao->subeixo_id)) {
                            $section->addText("Ação: {$acao->titulo}", ['underline' => Font::UNDERLINE_SINGLE]);

                            $section->addText($acao->getAttributeLabel('numero'), $fontStyleLabel);
                            $section->addText($acao->numero);

                            $section->addText($acao->getAttributeLabel('descricao'), $fontStyleLabel);
                            Html::addHtml($section, Universal::stripTags($acao->descricao), false, false);

                            $section->addText($acao->getAttributeLabel('unidade_executora'), $fontStyleLabel);
                            $section->addText($acao->unidadeExecutora->nome);

                            if ($acao->acaoUnidadeApoios) {
                                $section->addText($acao->getAttributeLabel('unidadeApoioIds'), $fontStyleLabel);
                                $table = $section->addTable();

                                foreach ($acao->acaoUnidadeApoios as $acaoUnidadeApoio) {
                                    $table->addRow();
                                    $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])
                                        ->addText($acaoUnidadeApoio->unidadeAdministrativa->nome);
                                }

                                $section->addTextBreak();
                            }

                            if (!empty($acao->objetivo)) {
                                $section->addText($acao->getAttributeLabel('objetivo'), $fontStyleLabel);
                                Html::addHtml(
                                    $section,
                                    Universal::stripTags($acao->objetivo),
                                    false,
                                    false
                                );
                            }

                            if (!empty($acao->beneficio_instituicao)) {
                                $section->addText($acao->getAttributeLabel('beneficio_instituicao'), $fontStyleLabel);
                                Html::addHtml(
                                    $section,
                                    Universal::stripTags($acao->beneficio_instituicao),
                                    false,
                                    false
                                );
                            }
                        }
                    }
                }

                if ($eixo->subeixos) {
                    foreach ($eixo->subeixos as $subeixo) {
                        $section->addText("Subeixo: {$subeixo->titulo}", ['color' => '65a47b']);

                        Html::addHtml($section, Universal::stripTags($subeixo->descricao), false, false);

                        if ($subeixo->acaos) {
                            foreach ($subeixo->acaos as $acao) {
                                $section->addText("Ação: {$acao->titulo}", ['underline' => Font::UNDERLINE_SINGLE]);

                                $section->addText($acao->getAttributeLabel('numero'), $fontStyleLabel);
                                $section->addText($acao->numero);

                                $section->addText($acao->getAttributeLabel('descricao'), $fontStyleLabel);
                                Html::addHtml($section, Universal::stripTags($acao->descricao), false, false);

                                $section->addText($acao->getAttributeLabel('unidade_executora'), $fontStyleLabel);
                                $section->addText($acao->unidadeExecutora->nome);

                                if ($acao->acaoUnidadeApoios) {
                                    $section->addText($acao->getAttributeLabel('unidadeApoioIds'), $fontStyleLabel);
                                    $table = $section->addTable();

                                    foreach ($acao->acaoUnidadeApoios as $acaoUnidadeApoio) {
                                        $table->addRow();
                                        $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])
                                            ->addText($acaoUnidadeApoio->unidadeAdministrativa->nome);
                                    }

                                    $section->addTextBreak();
                                }

                                if (!empty($acao->objetivo)) {
                                    $section->addText($acao->getAttributeLabel('objetivo'), $fontStyleLabel);
                                    Html::addHtml(
                                        $section,
                                        Universal::stripTags($acao->objetivo),
                                        false,
                                        false
                                    );
                                }

                                if (!empty($acao->beneficio_instituicao)) {
                                    $section->addText(
                                        $acao->getAttributeLabel('beneficio_instituicao'),
                                        $fontStyleLabel
                                    );
                                    Html::addHtml(
                                        $section,
                                        Universal::stripTags($acao->beneficio_instituicao),
                                        false,
                                        false
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($modelPlano->validacao) {
            $modelValidacao = $modelPlano->validacao;

            $section->addTitle('Validação geral');

            $section->addText($modelValidacao->getAttributeLabel('data_inicio'), $fontStyleLabel);
            $section->addText(Universal::convertDate($modelValidacao->data_inicio));

            $section->addText($modelValidacao->getAttributeLabel('data_conclusao'), $fontStyleLabel);
            $section->addText(Universal::convertDate($modelValidacao->data_conclusao));

            $section->addText($modelValidacao->getAttributeLabel('stakeholderIds'), $fontStyleLabel);

            $table = $section->addTable();
            foreach ($modelValidacao->validacaoStakeholders as $validacaoStakeholder) {
                $stakeholder = $validacaoStakeholder->stakeholder;

                $table->addRow();

                $isBold = !is_null($stakeholder->orgao_id);

                $table->addCell(9000, ['borderSize' => 1, 'borderStyle' => Border::DASHED])
                    ->addText($stakeholder->nome, ['bold' => $isBold]);
            }

            $section->addTextBreak();

            $section->addText($modelValidacao->getAttributeLabel('info_complementar'), $fontStyleLabel);
            Html::addHtml($section, Universal::stripTags($modelValidacao->info_complementar), false, false);
        }

        $filename = 'ProgramaIntegridadeParcial_' . $modelPlano->id . date_timestamp_get(date_create()) . Yii::$app->security->generateRandomString(4);
        $path = Yii::getAlias("@webroot") . "/tmp/{$filename}.docx";

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("tmp/{$filename}.docx");

        Yii::$app->response->sendFile($path, 'programa_integridade_parcial.docx')->on(Response::EVENT_AFTER_SEND, function ($event) {
            unlink($event->data);
        }, $path);

        return true;
        /*if (!Universal::temPermissao('plano-integridade-elaborar', $modelPlano)) {
            return $this->redirect(['/site/acesso-negado']);
        }

        return $this->renderPartial('index', [
            'modelPlano' => $modelPlano
        ]);*/
    }
}
