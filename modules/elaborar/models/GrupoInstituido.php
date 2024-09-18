<?php

namespace app\modules\elaborar\models;

use Yii;
use yii\web\JsExpression;
use app\models\Status;

/**
 * This is the model class for table "grupo_instituido".
 *
 * @property int $id
 * @property int $grupo_id
 * @property int $formalmente
 * @property string|null $nome_numero
 * @property string|null $data_publicacao
 * @property int|null $dias_conclusao
 * @property string|null $data_prevista_conclusao
 * @property string|null $link
 * @property string|null $data_inicio
 * @property int $order
 * @property int $tipo
 * @property string $created_at
 *
 * @property Grupo $grupo
 */
class GrupoInstituido extends \yii\db\ActiveRecord
{
    public const SCENARIO_ADITIONAL = 'aditional';
    public const SCENARIO_ADITIONAL_SERVIDOR = 'aditionalServidor';
    public const TIPO_GRUPO = 1;
    public const TIPO_SERVIDOR = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grupo_instituido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order', 'tipo'], 'required'],
            [['formalmente'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['grupo_id', 'formalmente', 'dias_conclusao', 'order', 'tipo'], 'integer'],
            [['data_prevista_conclusao', 'data_inicio', 'created_at'], 'safe'],
            [['nome_numero', 'link'], 'string', 'max' => 255],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grupo::className(), 'targetAttribute' => ['grupo_id' => 'id']],
            [['nome_numero', 'link'], 'default', 'value' => null],
            [['nome_numero', 'data_publicacao', 'dias_conclusao', 'link'], 'required', 'on' => self::SCENARIO_ADITIONAL],
            [['nome_numero', 'data_publicacao', 'link'], 'required', 'on' => self::SCENARIO_ADITIONAL_SERVIDOR],

            [['nome_numero', 'data_publicacao', 'dias_conclusao', 'link'], 'required', 'when' => function ($model) {
                return $model->formalmente === Status::STATUS_SIM;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"GrupoInstituido[formalmente]\"]:checked').val() == " . Status::STATUS_SIM . "
            }")],

            [['data_inicio'], 'required', 'when' => function ($model) {
                return $model->formalmente === Status::STATUS_NAO;
            }, 'whenClient' => new JsExpression("function (attribute, value) {
                return $('input[name=\"GrupoInstituido[formalmente]\"]:checked').val() == " . Status::STATUS_NAO . "
            }")],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADITIONAL] = ['grupo_id', 'nome_numero', 'data_publicacao', 'dias_conclusao', 'link'];
        $scenarios[self::SCENARIO_ADITIONAL_SERVIDOR] = [
            'grupo_id',
            'nome_numero',
            'data_publicacao',
            'link',
            'tipo'
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'grupo_id' => 'Grupo ID',
            'formalmente' => 'A comissão de integridade foi instituída formalmente?',
            'data_publicacao' => 'Data de publicação do ato normativo',
            'dias_conclusao' => 'Dias previstos para a conclusão do processo de formulação do programa de integridade',
            'data_prevista_conclusao' => 'Data prevista para conclusão do PI',
            'link' => 'Link da publicação do ato normativo',
            'data_inicio' => 'Data de início',
            'order' => 'Order',
            'tipo' => 'Tipo',
            'created_at' => 'Created At',
        ];

        switch ($this->scenario) {
            case self::SCENARIO_ADITIONAL:
                $labels['nome_numero'] = 'Nome e número do ato normativo que prorroga o prazo do processo de formulação do programa de integridade';
                break;
            case self::SCENARIO_ADITIONAL_SERVIDOR:
                $labels['nome_numero'] = 'Nome e número do ato normativo que altera composição da comissão de integridade';
                break;
            default:
                $labels['nome_numero'] = 'Nome e número do ato normativo que instituiu a comissão de integridade responsável pela formulação do programa de integridade da organização';
        }

        return $labels;
    }

    /**
     * Gets query for [[Grupo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(Grupo::className(), ['id' => 'grupo_id']);
    }
}
