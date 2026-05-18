<?php

namespace app\components\helpers;

use app\models\Historico;
use DateTime;
use yii\db\Query;
use yii\helpers\Html;
use app\models\UnidadeAdministrativa;
use app\models\User;
use app\modules\executar\models\AcaoServidor;
use yii\helpers\ArrayHelper;

class Audit
{
    /**
     * @param object $historico objeto do modelo [Historico]
     * @param string $campoLabel Label definida no model para determinado campo
     * @param array $behaviors behaviors declaros no modelo
     *
     * @return mixed
     */
    public static function prepareData($historico, $campoLabel, $behaviors = null)
    {
        if (!in_array($historico->campo, ['acao_id', 'evidencia_arquivo'])) {
            $relationKey = false;

            $relations = [];

            if (!is_null($behaviors)) {
                $relations = $behaviors['auditBehaviors']->relations;

                $relationKey = array_search($historico->campo, array_column($relations, 'field'));
            }

            $user = User::findOne($historico->usuario_id);

            $historico->antigo_valor = self::prepareField(
                $relationKey,
                $relations,
                $historico->campo,
                $historico->antigo_valor,
                $historico->multiple
            );

            $historico->novo_valor = self::prepareField(
                $relationKey,
                $relations,
                $historico->campo,
                $historico->novo_valor,
                $historico->multiple
            );

            $isDelete = $historico->action == 'Delete';

            $class =  $isDelete ? 'bg-light-danger' : 'bg-light-success';

            $operator = $isDelete ? '-' : '+';

            $html = Html::beginTag(
                'div',
                ['class' => "border-radius mb-3 p-3 {$class}"]
            );

            $html .= Html::tag('p', "{$operator} {$campoLabel}", ['class' => 'font-weight-bold']);

            $html .= Html::tag('hr');

            $html .= Html::tag(
                'p',
                Html::tag('span', 'Valor antigo: ', ['class' => 'font-weight-bold']) .
                    Html::tag(
                        'span',
                        strip_tags(Universal::valueField($historico->antigo_valor, null, true))
                    )
            );

            $html .= Html::tag(
                'p',
                Html::tag('span', 'Novo valor: ', ['class' => 'font-weight-bold']) .
                    Html::tag('span', strip_tags(Universal::valueField($historico->novo_valor, null, true))),
                ['class' => 'mb-3']
            );

            $createdAt = new DateTime($historico->created_at);

            $createdAt = $createdAt->format('d/m/Y H:i');

            $userPerfil = is_null($historico->usuario_perfil) ?
                $user->authAssignment->item_name :
                $historico->usuario_perfil;

            $html .= Html::tag(
                'p',
                Html::tag(
                    'span',
                    "Alteração realizada em " . $createdAt .
                        " por {$user->nome} ({$userPerfil})"
                ),
                ['class' => 'font-weight-bold small']
            );

            $html .= Html::endTag('div');

            return $html;
        }
    }

    /**
     * @param object $historico objeto do modelo [Historico]
     * @param integer $acaoId Número identificador da Acao
     * @param string $campoLabel Label definida no model para determinado campo
     * @param integer $oldRegister Número identificado do ultimo registro exibido
     * @param boolean $closeRegister Usado para verificar a necessidade de fechar as tags divs[.card && .card-body], quando a função for executada novamente
     * @param integer $lastRegister Usado para verificar a necessidade de fechar as tags divs[.card && .card-body], quando a função não for executada novamente
     *
     * @return mixed
     */
    public static function prepareDataServidor($historico, $acaoId, $campoLabel, $oldRegister, $closeRegister, $lastRegister)
    {
        $differentRegister = $historico->id_registro != $oldRegister;

        $isDelete = $historico->action == 'Delete';

        $class =  $isDelete ? 'bg-light-danger' : 'bg-light-success';

        $operator = $isDelete ? '-' : '+';

        $html = '';

        if ($differentRegister) {
            if ($closeRegister) {
                $html .= Html::endTag('div');

                $html .= Html::endTag('div');
            }

            $html .= Html::beginTag(
                'div',
                ['class' => "card mt-0 mb-3"]
            );

            $html .= Html::beginTag('div', ['class' => 'card-body']);
        }

        $user = User::findOne($historico->usuario_id);

        if ($historico->campo === 'unidade_administrativa_id') {
            if (!empty($historico->antigo_valor)) {
                $unidadeAdministrativa = UnidadeAdministrativa::findOne($historico->antigo_valor);

                $historico->antigo_valor = $unidadeAdministrativa->nome;
            }

            if (!empty($historico->novo_valor)) {
                $unidadeAdministrativa = UnidadeAdministrativa::findOne($historico->novo_valor);

                $historico->novo_valor = $unidadeAdministrativa->nome;
            }
        }

        $servidorResponsavel = AcaoServidor::findOne(['acao_id' => $acaoId, 'tipo' => AcaoServidor::TIPO_RESPONSAVEL]);

        $html .= Html::beginTag('div', ['class' => "border-radius mb-3 p-3 {$class}"]);

        $html .= Html::tag(
            'p',
            "{$operator} {$campoLabel}",
            ['class' => 'font-weight-bold']
        );

        $html .= Html::tag('hr');

        $html .= Html::tag(
            'p',
            Html::tag('span', 'Valor antigo: ', ['class' => 'font-weight-bold']) .
                Html::tag(
                    'span',
                    Universal::valueField($historico->antigo_valor, null, true)
                )
        );

        $html .= Html::tag(
            'p',
            Html::tag('span', 'Novo valor: ', ['class' => 'font-weight-bold']) .
                Html::tag('span', Universal::valueField($historico->novo_valor, null, true)),
        );

        $html .= Html::tag(
            'p',
            Html::tag('span', 'Tipo: ', ['class' => 'font-weight-bold']) .
                Html::tag('span', $servidorResponsavel->servidor_id === $historico->id_registro ? 'Responsável' : 'Envolvido'),
            ['class' => 'mb-3']
        );

        $userPerfil = is_null($historico->usuario_perfil) ?
            $user->authAssignment->item_name :
            $historico->usuario_perfil;

        $html .= Html::tag(
            'p',
            Html::tag(
                'span',
                "Alteração realizada em " . Universal::convertDate($historico->created_at) .
                    " por {$user->nome} ({$userPerfil})"
            ),
            ['class' => 'font-weight-bold small']
        );

        $html .= Html::endTag('div');

        if ($lastRegister) {
            $html .= Html::endTag('div');

            $html .= Html::endTag('div');
        }

        return $html;
    }

    /**
     * @param integer $relationKey Chave correspondente ao campo, declarado no behaviors do modelo
     * @param array $relations Array de relacoes declaradas no behaviors do modelo
     * @param string $field Nome do campo salvo no historico
     * @param string $value Valor a ser formatado
     * @param boolean $isMultiple Usada para saber se o campo é multiplo
     *
     * @return string
     */
    public static function prepareField($relationKey, $relations, $field, $value, $isMultiple)
    {
        if (is_int($relationKey)) {
            $condition = $isMultiple ? explode(', ', $value) : $value;

            $queryBase = (new Query())
                ->select('nome')
                ->from($relations[$relationKey]['tableSearch']);

            $valueQuery = $queryBase->where(['in', 'id', $condition])
                ->all();

            return implode(', ', array_column($valueQuery, 'nome'));
        } elseif (DateTime::createFromFormat('Y-m-d', $value) != false) {
            return Universal::convertDate($value);
        } elseif (strpos($field, 'orcamento') !== false) {
            return Universal::convertCurrency($value);
        }

        return $value;
    }

    /**
     * @param string $model Nome do modelo que foi alterado
     * @param integer $acaoId Número identificador da acao
     *
     * @return array
     */
    public static function getRegisterIds($model, $acaoId)
    {
        $registers = Historico::find()
            ->where(['campo' => 'acao_id', 'model' => $model])
            ->andWhere([
                'or',
                ['antigo_valor' => $acaoId],
                ['novo_valor' => $acaoId]
            ])
            ->all();

        $registerIds = array_unique(ArrayHelper::getColumn($registers, 'id_registro'));

        return $registerIds;
    }

    /**
     * @param string $model Nome do modelo que foi alterado
     * @param array $registerIds Array com os identificadores que serão buscados
     * @param boolean $withFilter Usado para saber se a consulta será com ou sem filtro
     * @param string $dataInicio
     * @param string $dataFim
     *
     * @return object
     */
    public static function getHistorico($model, $registerIds, $withFilter, $dataInicio, $dataFim)
    {
        $queryBase = Historico::find()
            ->where(['model' => $model])
            ->andWhere(['in', 'id_registro', $registerIds]);

        if ($withFilter) {
            $queryBase->andWhere(['between', 'created_at', $dataInicio, $dataFim]);
        }

        $historicos = $queryBase->orderBy(['id_registro' => SORT_ASC])
            ->all();

        return $historicos;
    }
}
