<?php

namespace app\models;

use Yii;
use yii\web\JsExpression;
use app\behaviors\AuditBehaviors;
use app\modules\elaborar\models\Eixo;
use app\modules\elaborar\models\Subeixo;
use app\modules\executar\models\AcaoExecucao;
use app\modules\executar\models\AcaoServidor;
use app\modules\executar\models\AcaoTipo;
use app\modules\monitorar\models\AcaoMonitoramento;

/**
 * This is the model class for table "acao".
 *
 * @property int $id
 * @property int $eixo_id
 * @property int|null $subeixo_id
 * @property int $numero
 * @property string $titulo
 * @property string $descricao
 * @property int $unidade_executora
 * @property string|null $objetivo
 * @property string|null $beneficio_instituicao
 * @property string|null $classificacao
 * @property string|null $previsao_inicio
 * @property string|null $previsao_conclusao
 * @property float|null $orcamento_previsto
 * @property int $status
 * @property int|null $acao_referencia_id
 * @property string $created_at
 *
 * @property Acao $acaoReferencia
 * @property Eixo $eixo
 * @property Subeixo $subeixo
 * @property UnidadeAdministrativa $unidadeExecutora
 * @property AcaoExecucao $acaoExecucao
 * @property AcaoServidor $acaoServidorResponsavel
 * @property AcaoServidor[] $acaoServidorsEnvolvido
 * @property AcaoMonitoramento $acaoMonitoramento
 * @property AcaoTipo[] $acaoTipos
 * @property AcaoUnidadeApoio[] $acaoUnidadeApoios
 * @property AcaoAvaliacaoRecomendacao[] $acaoAvaliacaoRecomendacaos
 * @property AcaoAvaliacaoRecomendacao[] $periodAcaoAvaliacaoRecomendacaos
 * @property AcaoServidor[] $acaoServidors
 */
class Acao extends \yii\db\ActiveRecord
{
    public $unidadeApoioIds;
    public $tipoIds;
    public $executarAccordion;
    public $justificativa_unidade_executora;
    public $saveAudit;

    public const SCENARIO_EXECUTAR = 'executar';

    public const CLASSIFICACAO_ACAO_CONTINUA = 'Ação contínua';
    public const CLASSIFICACAO_ACAO_PONTUAL = 'Ação pontual';

    public const EXECUTAR_INFORMACOES = 1;
    public const EXECUTAR_RESPONSABILIDADE = 2;
    public const EXECUTAR_EXECUCAO = 3;
    public const EXECUTAR_MONITORAMENTO = 4;

    /**
     * Constantes utilizadas para realizar filtro no módulo monitoramento [pontos de ação]
     */
    public const FILTER_ATRASADA_INICIO_PREVISTO = 1;
    public const FILTER_ATRASADA_CONCLUSAO_PREVISTO = 2;
    public const FILTER_RISCO_N_IMPLEMENTACAO_ALTO = 3;
    public const FILTER_STATUS_N_INICIALIZA = 4;
    public const FILTER_STATUS_EM_ANDAMENTO = 5;
    public const FILTER_STATUS_CONCLUIDA = 6;
    public const FILTER_STATUS_DESCONTINUADA = 7;
    public const FILTER_MONITORAMENTO_RECOMENDACAO_N_RESPONDIDA = 8;
    public const FILTER_MONITORAMENTO_RECOMENDACAO_RESPONDIDA = 9;
    public const FILTER_CONTROLE_INTERNO_RECOMENDACAO_N_RESPONDIDA = 10;
    public const FILTER_CONTROLE_INTERNO_RECOMENDACAO_RESPONDIDA = 11;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao';
    }

    public function behaviors()
    {
        return [
            'auditBehaviors' => [
                'class' => AuditBehaviors::className(),
                'relations' => [
                    [
                        'field' => 'unidadeApoioIds',
                        'name' => 'acaoUnidadeApoios',
                        'key' => 'unidade_administrativa_id',
                        'tableSearch' => 'unidade_administrativa',
                    ],
                    ['field' => 'tipoIds', 'name' => 'acaoTipos', 'key' => 'tipo_id', 'tableSearch' => 'tipo'],
                    ['field' => 'unidade_executora', 'tableSearch' => 'unidade_administrativa'],
                ],
                'extraFields' => ['unidadeApoioIds', 'tipoIds', 'justificativa_unidade_executora', 'saveAudit'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eixo_id', 'numero', 'titulo', 'descricao', 'unidade_executora', 'status'], 'required'],
            [['eixo_id', 'subeixo_id', 'numero', 'unidade_executora', 'executarAccordion', 'status', 'saveAudit', 'acao_referencia_id'], 'integer'],
            [['descricao', 'objetivo', 'beneficio_instituicao'], 'string'],
            [['previsao_inicio', 'previsao_conclusao', 'created_at'], 'safe'],
            [['orcamento_previsto'], 'number'],
            [['titulo', 'classificacao'], 'string', 'max' => 255],
            [['unidadeApoioIds', 'tipoIds'], 'each', 'rule' => ['integer']],
            [['objetivo', 'beneficio_instituicao', 'acao_referencia_id'], 'default', 'value' => null],
            [['acao_referencia_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acao::className(), 'targetAttribute' => ['acao_referencia_id' => 'id']],
            [['eixo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eixo::className(), 'targetAttribute' => ['eixo_id' => 'id']],
            [['subeixo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subeixo::className(), 'targetAttribute' => ['subeixo_id' => 'id']],
            [['unidade_executora'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadeAdministrativa::className(), 'targetAttribute' => ['unidade_executora' => 'id']],

            [['classificacao', 'previsao_inicio', 'tipoIds'], 'required', 'when' => function ($model) {
                return $model->executarAccordion === self::EXECUTAR_INFORMACOES;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('#acao-executaraccordion').val() == '" . self::EXECUTAR_INFORMACOES . "'
            }")],

            [['objetivo'], 'required', 'when' => function ($model) {
                return empty($model->beneficio_instituicao);
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('#acao-beneficio_instituicao').val().length == 0
            }")],

            [['beneficio_instituicao'], 'required', 'when' => function ($model) {
                return empty($model->objetivo);
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('#acao-objetivo').val().length == 0
            }")],

            [['previsao_conclusao'], 'required', 'when' => function ($model) {
                return $model->classificacao === self::CLASSIFICACAO_ACAO_PONTUAL;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('input[name=\"Acao[classificacao]\"]:checked').val() == '" . self::CLASSIFICACAO_ACAO_PONTUAL . "';
            }")],

            [['justificativa_unidade_executora'], 'required', 'when' => function ($model) {
                return $model->unidade_executora != $this->unidade_executora;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('#acao-unidade_executora').val() != '" . $this->unidade_executora . "';
            }")]
        ];
    }

    /**
     * Scenario
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_EXECUTAR] = [
            'classificacao',
            'previsao_inicio',
            'previsao_conclusao',
            'orcamento_previsto',
            'tipoIds',
            'unidade_executora',
            'unidadeApoioIds',
            'executarAccordion',
            'justificativa_unidade_executora',
        ];
        return $scenarios;
    }

    /**
     * Salvando Historico
     */
   /*  public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ((int) $this->executarAccordion === self::EXECUTAR_RESPONSABILIDADE) {
            $changedAttributes['unidadeApoioIds'] = implode(
                ", ",
                ArrayHelper::getColumn($this->acaoUnidadeApoios, 'unidade_administrativa_id')
            );

            foreach($changedAttributes as $nameField => $value) {
                if (strpos($nameField, 'Ids')) {
                    $newValue = !empty($this->$nameField) ? implode(", ", $this->$nameField) : $this->$nameField;
                    $isMultiple = Status::STATUS_SIM;
                } else {
                    $newValue = (string) $this->$nameField;
                    $isMultiple = Status::STATUS_NAO;
                }

                if ($newValue != $value) {
                    $newHistorico = new Historico();
                    $newHistorico->model = $this->tableName();
                    $newHistorico->id_registro = $this->id;
                    $newHistorico->campo = $nameField;
                    $newHistorico->antigo_valor = (string) $value;
                    $newHistorico->novo_valor = $newValue;

                    $justificativaField = "justificativa_{$nameField}";
                    $newHistorico->justificativa = $this->$justificativaField ?? null;

                    $newHistorico->multiple = $isMultiple;
                    $newHistorico->usuario_id = User::getIdentidade('id');

                    $newHistorico->save();
                }
            }
        }
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eixo_id' => 'Eixo',
            'subeixo_id' => 'Subeixo',
            'numero' => 'Número da ação',
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'unidade_executora' => 'Unidade administrativa executora',
            'objetivo' => 'Objetivos da ação',
            'beneficio_instituicao' => 'Benefícios para a instituição',
            'unidadeApoioIds' => 'Unidade administrativa de apoio',
            'classificacao' => 'Classificação',
            'tipoIds' => 'Tipo',
            'previsao_inicio' => 'Previsão de início da ação',
            'previsao_conclusao' => 'Previsão de conclusão da ação',
            'orcamento_previsto' => 'Orçamento previsto',
            'justificativa_unidade_executora' => 'Justificativa',
            'status' => 'Status',
            'acao_referencia_id' => 'Acao Referencia ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Montando o array com as classificações disponiveis para a ação
     *
     * @return array|string
     */
    public static function getClassificacao($key = null)
    {
        $arr = [
            self::CLASSIFICACAO_ACAO_CONTINUA => 'Ação contínua',
            self::CLASSIFICACAO_ACAO_PONTUAL => 'Ação pontual',
        ];

        return is_null($key) ? $arr : $arr[$key];
    }

    /**
     * Gets query for [[AcaoReferencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoReferencia()
    {
        return $this->hasOne(Acao::className(), ['id' => 'acao_referencia_id']);
    }

    /**
     * Gets query for [[Eixo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEixo()
    {
        return $this->hasOne(Eixo::className(), ['id' => 'eixo_id']);
    }

    /**
     * Gets query for [[Subeixo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubeixo()
    {
        return $this->hasOne(Subeixo::className(), ['id' => 'subeixo_id']);
    }

    /**
     * Gets query for [[UnidadeExecutora]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadeExecutora()
    {
        return $this->hasOne(UnidadeAdministrativa::className(), ['id' => 'unidade_executora']);
    }

    /**
     * Gets query for [[AcaoExecucao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucao()
    {
        return $this->hasOne(AcaoExecucao::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[AcaoMonitoramentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoMonitoramento()
    {
        return $this->hasOne(AcaoMonitoramento::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[AcaoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoServidorResponsavel()
    {
        return $this->hasOne(AcaoServidor::className(), ['acao_id' => 'id'])
            ->onCondition(['tipo' => AcaoServidor::TIPO_RESPONSAVEL]);
    }

    /**
     * Gets query for [[AcaoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoServidorsEnvolvido()
    {
        return $this->hasMany(AcaoServidor::className(), ['acao_id' => 'id'])
            ->onCondition(['tipo' => AcaoServidor::TIPO_ENVOLVIDO]);
    }

    /**
     * Gets query for [[AcaoServidors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoServidors()
    {
        return $this->hasMany(AcaoServidor::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[AcaoTipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoTipos()
    {
        return $this->hasMany(AcaoTipo::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[AcaoUnidadeApoios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoUnidadeApoios()
    {
        return $this->hasMany(AcaoUnidadeApoio::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[Historico]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricos()
    {
        return $this->hasMany(Historico::className(), ['id_registro' => 'id'])
            ->onCondition(['model' => 'Acao']);
    }

    /**
     * Gets query for [[Historico]] with parameter
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodHistoricos($dataInicio, $dataFim)
    {
        return $this->hasMany(Historico::className(), ['id_registro' => 'id'])
            ->onCondition(['model' => 'Acao'])
            ->andOnCondition(['between', 'created_at', $dataInicio, $dataFim]);
    }

    /**
     * Gets query for [[AcaoAvaliacaoRecomendacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoAvaliacaoRecomendacaos()
    {
        return $this->hasMany(AcaoAvaliacaoRecomendacao::className(), ['acao_id' => 'id']);
    }

    /**
     * Gets query for [[AcaoAvaliacaoRecomendacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodAcaoAvaliacaoRecomendacaos($dataInicio, $dataFim)
    {
        return $this->hasMany(AcaoAvaliacaoRecomendacao::className(), ['acao_id' => 'id'])
            ->where(['between', 'created_at', $dataInicio, $dataFim]);
    }
}
