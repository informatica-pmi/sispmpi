<?php

namespace app\components\helpers;

use Yii;
use app\base\Txt;
use app\models\Arquivo;
use app\models\Status;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Universal
{
    /**
     * Retorna os dados em formato compatível com dropdown
     * @param $model Caminho completo (fully qualified name) do Model que representa os dados
     * @param boolean $filterStatus Se deseja listar os ativos
     * @param string $key Chave primária da tabela
     * @param string $value Valor a ser exibido no dropdown
     * @param null $sortField Campo para ordenar
     * @param null $sort Tipo de ordenação
     * @return array
     */
    public static function getDropDown($model, $filterStatus = false, $key = 'id', $value = 'nome', $sortField = null, $sort = null)
    {
        $condicao = ['status' => Status::STATUS_ATIVO];

        if (!is_null($sortField) && !is_null($sort)) {
            $data = $filterStatus ? $model::find()->where($condicao)->orderBy([$sortField => $sort])->all() : $model::find()->orderBy([$sortField => $sort])->all();
            return ArrayHelper::map($data, $key, $value);
        }

        $data = $filterStatus ? $model::find()->where($condicao)->orderBy([$value => SORT_ASC])->all() : $model::find()->orderBy([$value => SORT_DESC])->all();
        return ArrayHelper::map($data, $key, $value);
    }

    /**
     * Verificando permissão
     * @param $permissao
     * @param null $post
     * @return bool
     */
    public static function temPermissao($permissao, $post = null)
    {
        if (is_null($post) && Yii::$app->user->can($permissao) || !is_null($post) && Yii::$app->user->can($permissao, ['post' => $post])) {
            return true;
        }

        return false;
    }

    /**
     * Gerando Icone
     * @param $icon
     * @return string
     */
    public static function icon($icon)
    {
        return Html::tag('i', '', ['class' => $icon]);
    }

    /**
     * Verifica se o valor é vazio, se ele não for retorna o nome do campo de acordo com o relacionamento.
     * @param object|string $var objeto do banco de dados ou valor que deseja verificar
     * @param string $field nome do campo da tabela que quer buscar
     * @param bool $returnString se deseja retornar uma string prédefinada no lugar do null
     * @param string $string mensagem que vai retornar
     * @param string $tag tag que será retornada junto a string
     * @return mixed
     */
    public static function valueField(
        $var,
        $field = null,
        $returnString = false,
        $string = 'Não se aplica/Não definido',
        $tag = 'span'
    ) {
        if (!is_null($var) && !empty($var)) {
            if (is_null($field)) {
                return $var;
            }

            return $var->$field;
        }

        return !$returnString ? null : "<{$tag}>$string</{$tag}>";
    }

    /**
     * @param $field
     * @return string
     */
    public static function notSet($field)
    {
        return empty($field) ? "<span class='not-set'>" . Txt::$t['null'] . "</span>" : $field;
    }

    /**
     * Return alert
     * @param string $key
     * @param string $value
     */
    public static function flash($key = 'success', $value = 'Registro salvo com sucesso.')
    {
        return Yii::$app->session->setFlash($key, $value);
    }

    /**
     * @param $data
     * @param bool $db
     * @return string
     */
    public static function convertDate($data, $db = false)
    {
        $formatter = Yii::$app->formatter;
        return $db ? $formatter->asDate($data, 'php:Y-m-d') : $formatter->asDate($data, 'php:d/m/Y');
    }

    /**
     * @param $value
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function convertCurrency($value)
    {
        return Yii::$app->formatter->asCurrency($value);
    }

    /**
     * Gerando Modal
     * @param $header
     * @param $id
     * @param $size
     */
    public static function modal($header, $id, $size = Modal::SIZE_DEFAULT)
    {
        Modal::begin([
            'title' => $header,
            'id' => $id,
            'size' => $size,
            'headerOptions' => ['class' => 'bg-light']
        ]);

        echo "<div id='modalContent'></div>";

        Modal::end();
    }

    /**
     * Deletando arquivo
     * @param integer $id Id do arquivo a ser deletado
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteArquivo($id)
    {
        $model = Arquivo::findOne($id);

        if (file_exists($model->path)) {
            unlink($model->path);
        }

        return $model->delete();
    }

    /**
     * @param string $date
     * @param string $interval
     *
     * @return string
     */
    public static function addDays($date, $interval)
    {
        $intervalTrimmed = trim($interval);

        $dateResult = new \DateTime($date);
        $dateResult->add(new \DateInterval("P{$intervalTrimmed}D"));

        return $dateResult->format('Y-m-d');
    }

    /**
     * Intervalo entre as datas
     * @param string $firstDate
     * @param string $lastDate
     * @return int Interval between dates
     */
    public static function intervalDates($firstDate, $lastDate)
    {
        $firstDateTime = new \DateTime($firstDate);
        $lastDateTime = new \DateTime($lastDate);

        $interval = $firstDateTime->diff($lastDateTime);
        return $interval->format('%R%a');
    }

    /**
     * calculando a porcentagem para exibir nos graficos
     * @param integer $value
     * @param integer $total
     * @return int|string
     */
    public static function calcPorcentagem($value, $total)
    {
        if (!empty($total)) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $result = ($item * 100) / $total;
                    $array[] = is_int($result) ? $result : number_format($result, 2);
                }
            } else {
                $result = ($value * 100) / $total;
                $result = is_int($result) ? $result : number_format($result, 2);
            }

            return isset($array) ? $array : $result;
        }

        return 0;
    }

    /**
     * Arredondando orçamento
     */
    public static function arredondaOrcamento($orcamento)
    {
        $newOrcamento = floor($orcamento);
        $sizeOrcamento = strlen($newOrcamento);
        $newOrcamento = Yii::$app->formatter->asDecimal(round(Yii::$app->formatter->asInteger($orcamento), 1));

        switch (true) {
            case $sizeOrcamento > 12:
                echo "R$ {$newOrcamento} tri.";
                break;
            case $sizeOrcamento > 9:
                echo "R$ {$newOrcamento} bi.";
                break;
            case $sizeOrcamento > 6:
                echo "R$ {$newOrcamento} mi.";
                break;
            default:
                echo self::convertCurrency($orcamento);
        }
    }

    public static function formatInteger($value)
    {
        return Yii::$app->formatter->asInteger($value);
    }

    /**
     * Convertentendo status
     */
    public static function convertStatus($status)
    {
        return $status ?
            "<span class='badge badge-success p-2'>ATIVO</span>" :
            "<span class='badge badge-danger p-2'>INATIVO</span>";
    }

    /**
     * Removendo tags da string
     * @param $string
     * @param $allowTags
     * @return string
     */
    public static function stripTags($string, $allowTags = '<p><strong><em>')
    {
        return strip_tags($string, $allowTags);
    }
}
