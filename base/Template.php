<?php

namespace app\base;

class Template
{
    /**
     * Array de templates utilizadas com frequencia no sistema
     * @var array
     */
    public static $t = [
      'file' => '{label} <br> {input} {hint} {error}',
      'radio' => '{label} <br> {beginWrapper} {input} {hint} {error} {endWrapper}',
      'radioNotLabel' => '{beginWrapper} {input} {hint} {error} {endWrapper}',
    ];
}