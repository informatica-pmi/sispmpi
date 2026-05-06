<?php

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Widget TinyMCE customizado para o SisPMPI.
 * Força o carregamento via CDN para evitar erros de plugins locais ausentes.
 */
class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    /**
     * @var string Chave de API do TinyMCE
     */
    public $apiKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // 1. Captura a chave de API
        $this->apiKey = $this->apiKey ?: getenv('TINYMCE_API_KEY') ?: (Yii::$app->params['tinyMceApiKey'] ?? null);

        // 2. Se tivermos uma chave, forçamos o carregamento via CDN oficial
        // Isso impede que o sistema procure plugins na pasta /assets/ local
        if ($this->apiKey && $this->apiKey !== 'no-api-key') {
            $this->clientOptions['script_url'] = "https://cdn.tiny.cloud/1/{$this->apiKey}/tinymce/6/tinymce.min.js";
        }

        // 3. Configurações de interface
        $defaultClientOptions = [
            'menubar' => false,
            'statusbar' => false,
            'plugins' => [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            'toolbar' => "undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
            'language' => 'pt_BR',
            'branding' => false,
            'promotion' => false,
            'entity_encoding' => 'raw',
            'content_style' => 'body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }'
        ];

        $this->clientOptions = ArrayHelper::merge($defaultClientOptions, $this->clientOptions);
        $this->options = ArrayHelper::merge(['rows' => 6], $this->options);

        parent::init();
    }
}