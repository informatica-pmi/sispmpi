<?php

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    public $apiKey;

    public function init()
    {
        // 1. Tenta pegar a chave de todas as formas possíveis
        $this->apiKey = $this->apiKey ?: getenv('TINYMCE_API_KEY') ?: (Yii::$app->params['tinyMceApiKey'] ?? null);

        // 2. Se achamos a chave, forçamos ela no componente pai explicitamente
        if ($this->apiKey) {
            // Alguns wrappers usam a propriedade do objeto, outros esperam no config
            $this->apiKey = $this->apiKey; 
        }

        $this->language = 'pt_BR';

        $defaultClientOptions = [
            'menubar' => false,
            'statusbar' => false,
            'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify",
            'entity_encoding' => 'raw',
            'paste_as_text' => true,
            'language' => 'pt_BR',
            'content_style' => 'body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }',
            'branding' => false,
            'promotion' => false,
        ];

        $this->clientOptions = ArrayHelper::merge($defaultClientOptions, $this->clientOptions);
        $this->options = ArrayHelper::merge(['rows' => 6], $this->options);

        parent::init();
    }
}