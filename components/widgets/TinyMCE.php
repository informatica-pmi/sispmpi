<?php

namespace app\components\widgets;

/**
 * Custom TinyMCE widget to standardise configurations across SisPMPI.
 */
class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    /**
     * @var string The TinyMCE API Key for Cloud usage.
     */
    public $apiKey;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // Define a linguagem padrão
        $this->language = 'pt_BR';
        
        // Configurações do campo de texto (HTML)
        $this->options = array_merge(['rows' => 6], $this->options);
        
        // Configurações do editor (JavaScript)
        $this->clientOptions = array_merge([
            'menubar' => false,
            'statusbar' => false,
            'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify",
            'entity_encoding' => 'raw',
            'paste_as_text' => true,
            'content_style' => 'body { font-family: Calibri; }'
        ], $this->clientOptions);

        // Se a chave foi definida via DI (web.php), passamos para o componente pai
        if ($this->apiKey) {
            parent::$apiKey = $this->apiKey;
        }

        parent::init();
    }
}