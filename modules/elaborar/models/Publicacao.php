<?php

namespace app\modules\elaborar\models;

use app\models\User;
use Yii;
use yii\web\JsExpression;
use app\models\Arquivo;
use app\models\PlanoIntegridade;
use app\models\Status;

/**
 * This is the model class for table "publicacao".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property int $evento
 * @property string|null $data_evento
 * @property string|null $justificativa_evento
 * @property int $disponibilizado
 * @property string|null $endereco_disponibilizado
 * @property string|null $justificativa_disponibilizado
 * @property int $plano_comunicacao
 * @property int|null $plano_comunicacao_arquivo
 * @property int $plano_treinamento
 * @property int|null $plano_treinamento_arquivo
 * @property string $data_publicacao
 * @property string $nome_numero
 * @property string $link
 * @property int $plano_acao_arquivo
 * @property int $plano_integridade_arquivo
 * @property int $ciente_conteudo
 * @property int $ciente_conclusao
 * @property int|null $usuario_id
 * @property string $created_at
 *
 * @property Arquivo $planoAcaoArquivo
 * @property Arquivo $planoComunicacaoArquivo
 * @property Arquivo $planoIntegridadeArquivo
 * @property PlanoIntegridade $planoIntegridade
 * @property Arquivo $planoTreinamentoArquivo
 * @property User $usuario
 */
class Publicacao extends \yii\db\ActiveRecord
{
    public $integridadeFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'publicacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'evento', 'disponibilizado', 'data_publicacao', 'nome_numero', 'link', 'usuario_id'], 'required'],
            [['ciente_conteudo', 'ciente_conclusao'], 'required', 'message' => 'Para concluir o processo de formulação do programa e do plano de integridade é preciso marcar esta opção.'],
            [['plano_integridade_id', 'evento', 'disponibilizado', 'plano_comunicacao', 'plano_comunicacao_arquivo', 'plano_treinamento', 'plano_treinamento_arquivo', 'plano_acao_arquivo', 'plano_integridade_arquivo', 'ciente_conteudo', 'ciente_conclusao', 'usuario_id'], 'integer'],
            [['data_evento', 'data_publicacao', 'created_at'], 'safe'],
            [['justificativa_evento', 'endereco_disponibilizado', 'justificativa_disponibilizado', 'nome_numero', 'link'], 'string', 'max' => 255],
            [['justificativa_evento', 'endereco_disponibilizado', 'justificativa_disponibilizado'], 'default', 'value' => null],
            [['plano_acao_arquivo'], 'exist', 'skipOnError' => true, 'targetClass' => Arquivo::className(), 'targetAttribute' => ['plano_acao_arquivo' => 'id']],
            [['plano_comunicacao_arquivo'], 'exist', 'skipOnError' => true, 'targetClass' => Arquivo::className(), 'targetAttribute' => ['plano_comunicacao_arquivo' => 'id']],
            [['plano_integridade_arquivo'], 'exist', 'skipOnError' => true, 'targetClass' => Arquivo::className(), 'targetAttribute' => ['plano_integridade_arquivo' => 'id']],
            [['plano_integridade_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_id' => 'id']],
            [['plano_treinamento_arquivo'], 'exist', 'skipOnError' => true, 'targetClass' => Arquivo::className(), 'targetAttribute' => ['plano_treinamento_arquivo' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['integridadeFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'maxSize' => 1024 * 1024 * 10, 'on' => self::SCENARIO_DEFAULT],
            [['data_evento'], 'required', 'when' => function ($model) {
                return $model->evento === Status::STATUS_SIM;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"Publicacao[evento]\"]:checked').val() == " . Status::STATUS_SIM . "
            }")],

            [['justificativa_evento'], 'required', 'when' => function ($model) {
                return $model->evento === Status::STATUS_NAO;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"Publicacao[evento]\"]:checked').val() == " . Status::STATUS_NAO . "
            }")],

            [['endereco_disponibilizado'], 'required', 'when' => function ($model) {
                return $model->disponibilizado === Status::STATUS_SIM;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"Publicacao[disponibilizado]\"]:checked').val() == " . Status::STATUS_SIM . "
            }")],

            [['justificativa_disponibilizado'], 'required', 'when' => function ($model) {
                return $model->disponibilizado === Status::STATUS_NAO;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"Publicacao[disponibilizado]\"]:checked').val() == " . Status::STATUS_NAO . "
            }")],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plano_integridade_id' => 'Plano Integridade ID',
            'evento' => 'Será realizado Evento de Lançamento do Plano de Integridade?',
            'data_evento' => 'Data',
            'justificativa_evento' => 'Justificativa',
            'disponibilizado' => 'O Plano de Integridade será disponibilizado em sítio eletrônico com acesso ao público?',
            'endereco_disponibilizado' => 'Endereço do site',
            'justificativa_disponibilizado' => 'Justificativa',
            'plano_comunicacao' => 'Foi desenvolvido um plano de comunicação para divulgação do Plano de Integridade?',
            'plano_comunicacao_arquivo' => 'Plano Comunicacao Arquivo',
            'plano_treinamento' => 'Foi desenvolvido um plano de treinamento e capacitação quanto a temática integridade?',
            'plano_treinamento_arquivo' => 'Plano Treinamento Arquivo',
            'data_publicacao' => 'Data de publicação do normativo/ato',
            'nome_numero' => 'Nome e número do normativo/ato',
            'link' => 'Link da publicação',
            'plano_acao_arquivo' => 'Plano Acao Arquivo',
            'plano_integridade_arquivo' => 'Plano Integridade Arquivo',
            'ciente_conteudo' => 'Ciente do conteúdo',
            'ciente_conclusao' => 'Ciente da conclusão',
            'usuario_id' => 'Usuario ID',
            'created_at' => 'Created At',
            'integridadeFile' => 'Versão final do programa e do plano de integridade',
        ];
    }

    /**
     * Gets query for [[PlanoAcaoArquivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoAcaoArquivo()
    {
        return $this->hasOne(Arquivo::className(), ['id' => 'plano_acao_arquivo']);
    }

    /**
     * Gets query for [[PlanoComunicacaoArquivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoComunicacaoArquivo()
    {
        return $this->hasOne(Arquivo::className(), ['id' => 'plano_comunicacao_arquivo']);
    }

    /**
     * Gets query for [[PlanoIntegridadeArquivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridadeArquivo()
    {
        return $this->hasOne(Arquivo::className(), ['id' => 'plano_integridade_arquivo']);
    }

    /**
     * Gets query for [[PlanoIntegridade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoIntegridade()
    {
        return $this->hasOne(PlanoIntegridade::className(), ['id' => 'plano_integridade_id']);
    }

    /**
     * Gets query for [[PlanoTreinamentoArquivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoTreinamentoArquivo()
    {
        return $this->hasOne(Arquivo::className(), ['id' => 'plano_treinamento_arquivo']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_id']);
    }
}
