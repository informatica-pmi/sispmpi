<?php


namespace app\components\helpers;

use Yii;
use yii\helpers\Html;

class Button
{
    /**
     * @param $permissao
     * @param $post
     * @param $icon
     * @param $label
     * @param $url
     * @param bool $tooltip
     * @param string $tooltipTitle
     * @param string $btn
     * @param bool $confirm
     * @param string $mensagem
     * @param string $target
     * @return string
     */
    public static function generate($permissao, $post, $icon, $label, $url, $tooltip = false, $tooltipTitle = '', $btn = 'primary btn-sm', $confirm = false, $mensagem = 'Are you sure you want to delete this item?', $target = '_self')
    {
        if (Universal::temPermissao($permissao, $post)) {
            $options = $tooltip ? 
                ['class' => "btn btn-{$btn}", 'target' => "{$target}", 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => $tooltipTitle] : 
                ['class' => "btn btn-{$btn}", 'target' => "{$target}"];

            if (!$confirm) {
                return Html::a(Universal::icon($icon) . " {$label}", $url, $options);
            } else {
                $options = array_merge($options, [
                    'data' => [
                        'confirm' => Yii::t('yii', $mensagem),
                        'method' => 'post'
                    ]
                ]);
                return Html::a(Universal::icon($icon) . " {$label}", $url, $options);
            }
        }
    }
}