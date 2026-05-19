<?php

namespace app\components\widgets;

class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->language = 'pt_BR';
        $this->options = ['rows' => 6];
        $this->clientOptions = [
            'menubar' => false,
            'statusbar' => false,
            'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify",
            'entity_encoding' => 'raw',
            'paste_as_text' => true,
            'content_style' => 'body { font-family: Calibri; }',
            // Adicionando a chave de licença lida do ambiente
            'license_key' => getenv('TINYMCE_KEY'),
        ];
        parent::init();
    }
}
