<?php

namespace app\models;

use Yii;
use yii\web\JsExpression;

/**
 * This is the model class for table "plano_integridade_novo".
 *
 * @property int $id
 * @property int $plano_integridade_id
 * @property int $tipo
 * @property int $usuario_solicitante_id
 * @property int|null $autorizado
 * @property int $usuario_autorizador_id
 * @property string|null $justificativa
 * @property string $created_at
 *
 * @property PlanoIntegridade $planoIntegridade
 * @property User $usuarioAutorizador
 * @property User $usuarioSolicitante
 */
class PlanoIntegridadeNovo extends \yii\db\ActiveRecord
{
    public const SCENARIO_UPDATE = 'update';

    public const TIPO_NOVA_VERSAO = 1;
    public const TIPO_NOVA_EDICAO = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plano_integridade_novo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plano_integridade_id', 'tipo', 'usuario_solicitante_id'], 'required'],
            [['autorizado'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['plano_integridade_id', 'tipo', 'usuario_solicitante_id', 'autorizado', 'usuario_autorizador_id'], 'integer'],
            [['justificativa'], 'string'],
            [['created_at'], 'safe'],
            [['justificativa', 'autorizado'], 'default', 'value' => null],
            [['plano_integridade_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanoIntegridade::className(), 'targetAttribute' => ['plano_integridade_id' => 'id']],
            [['usuario_autorizador_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_autorizador_id' => 'id']],
            [['usuario_solicitante_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['usuario_solicitante_id' => 'id']],

            [['justificativa'], 'required', 'when' => function ($model) {
                return $model->autorizado === Status::STATUS_NAO;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('input[name=\"PlanoIntegridadeNovo[autorizado]\"]:checked').val() == '" . Status::STATUS_NAO . "';
            }"), 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * Scenarios
     *
     * @return void
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['plano_integridade_id', 'tipo', 'usuario_solicitante_id', 'autorizado', 'justificativa', 'usuario_autorizador_id'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plano_integridade_id' => 'Plano Integridade ID',
            'tipo' => 'Tipo',
            'usuario_solicitante_id' => 'Usuario Solicitante ID',
            'autorizado' => 'Deseja autorizar a solicitação de atualização do plano de integridade?',
            'usuario_autorizador_id' => 'Usuario Autorizador ID',
            'justificativa' => 'Justificativa',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Retornar array ou valor das solicitações padrões
     * @param null|integer $key
     * @return array|string
     */
    public static function getTipo($key = null)
    {
        $arr = [
            self::TIPO_NOVA_VERSAO => 'Elaboração de uma nova versão do atual plano de integridade',
            self::TIPO_NOVA_EDICAO => 'Elaboração de uma nova edição do plano de integridade'
        ];

        return is_null($key) ? $arr : $arr[$key];
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
     * Gets query for [[UsuarioAutorizador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioAutorizador()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_autorizador_id']);
    }

    /**
     * Gets query for [[UsuarioSolicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioSolicitante()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario_solicitante_id']);
    }
}
