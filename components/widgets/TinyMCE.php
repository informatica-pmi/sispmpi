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

            // Substitua o getenv() por $_SERVER
            // 'license_key' => $_SERVER['TINYMCE_KEY'] ?? 'chave_nao_encontrada',
	    'license_key' => getenv('TINYMCE_KEY') ?: 'chave_nao_encontrada',
        ];
        parent::init();
    }
}
