<?php

namespace app\modules\executar\models;

use Yii;
use app\models\Acao;
use app\behaviors\AuditBehaviors;
use app\models\Historico;

/**
 * This is the model class for table "acao_execucao".
 *
 * @property int $id
 * @property string|null $data_inicio
 * @property string|null $data_conclusao
 * @property float|null $orcamento_executado
 * @property string|null $observacoes
 * @property string|null $evidencias_sugeridas
 * @property string|null $evidencia_link
 * @property string|null $processo_sei
 * @property int $acao_id
 *
 * @property Acao $acao
 * @property AcaoExecucaoFatorLimitante[] $acaoExecucaoFatorLimitantes
 * @property AcaoExecucaoArquivo[] $acaoExecucaoArquivos
 */
class AcaoExecucao extends \yii\db\ActiveRecord
{
    public $fatoresLimitantesIds;
    public $saveAudit;

    /**
     * @var UploadedFile[]
     */
    public $evidenciaFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acao_execucao';
    }

    public function behaviors()
    {
        return [
            'auditBehaviors' => [
                'class' => AuditBehaviors::className(),
                'relations' => [
                    [
                        'field' => 'fatoresLimitantesIds',
                        'name' => 'acaoExecucaoFatorLimitantes',
                        'key' => 'fator_limitante_id',
                        'tableSearch' => 'fator_limitante',
                    ],
                ],
                'extraFields' => ['fatoresLimitantesIds', 'saveAudit'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_inicio', 'data_conclusao'], 'safe'],
            [['orcamento_executado'], 'number'],
            [['observacoes', 'evidencias_sugeridas'], 'string'],
            [['acao_id', 'saveAudit'], 'integer'],
            [['acao_id'], 'required'],
            [['fatoresLimitantesIds'], 'each', 'rule' => ['integer']],
            [['evidencia_link', 'processo_sei'], 'string', 'max' => 255],
            [
                ['acao_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Acao::className(),
                'targetAttribute' => ['acao_id' => 'id']
            ],
            [
                ['evidenciaFiles'],
                'file',
                'skipOnEmpty' => true,
                'extensions' => ['pdf', 'png', 'jpg', 'xls', 'xlsx', 'doc', 'docx'],
                'maxFiles' => 2,
                'maxSize' => 1024 * 1024 * 5
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_inicio' => 'Data de início',
            'data_conclusao' => 'Data de conclusão ou descontinuação',
            'orcamento_executado' => 'Orçamento executado',
            'observacoes' => 'Observações gerais',
            'evidencias_sugeridas' => 'Evidências sugeridas para controle da execução da ação',
            'evidencia_link' => 'Link',
            'processo_sei' => 'Processo eletrônico SEI ou link externo',
            'acao_id' => 'Acao ID',
            'fatoresLimitantesIds' => 'Fatores limitantes',
            'evidenciaFiles' => 'Arquivos',
        ];
    }

    /**
     * Gets query for [[Acao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcao()
    {
        return $this->hasOne(Acao::className(), ['id' => 'acao_id']);
    }

    /**
     * Gets query for [[AcaoExecucaoFatorLimitantes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucaoFatorLimitantes()
    {
        return $this->hasMany(AcaoExecucaoFatorLimitante::className(), ['acao_execucao_id' => 'id']);
    }

    /**
     * Gets query for [[Historico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricos()
    {
        return $this->hasMany(Historico::className(), ['id_registro' => 'id'])
            ->onCondition(['model' => 'AcaoExecucacao']);
    }

    /**
     * Gets query for [[AcaoExecucaoArquivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucaoArquivos()
    {
        return $this->hasMany(AcaoExecucaoArquivo::className(), ['acao_execucao_id' => 'id']);
    }
}
