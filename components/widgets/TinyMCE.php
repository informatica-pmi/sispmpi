<?php

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Widget TinyMCE customizado para o SisPMPI.
 * Configura o carregamento via CDN e aceita a licença GPL para evitar bloqueios.
 */
class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    /**
     * @var string Chave de API do TinyMCE.
     */
    public $apiKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // 1. Captura a chave de API (do .env ou params)
        $this->apiKey = $this->apiKey ?: getenv('TINYMCE_API_KEY') ?: (Yii::$app->params['tinyMceApiKey'] ?? null);

        // 2. Define a chave padrão se estiver vazia
        $key = ($this->apiKey && $this->apiKey !== 'no-api-key') ? $this->apiKey : 'no-api-key';
        
        // 3. Configurações de interface e aceitação de licença
        // O campo 'license_key' => 'gpl' é o que remove a mensagem de erro de licença nas versões 6 e 7
        $defaultClientOptions = [
            'license_key' => 'gpl', 
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
            'content_style' => 'body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }',
            // Forçamos o carregamento do script via CDN dentro das opções do cliente para evitar UnknownPropertyException
            'script_url' => "https://cdn.tiny.cloud/1/{$key}/tinymce/6/tinymce.min.js",
        ];

        // Se houver uma chave de API, alguns widgets da 2amigos usam cloudApiKey
        if ($key !== 'no-api-key') {
            $this->clientOptions['apiKey'] = $key;
        }

        $this->clientOptions = ArrayHelper::merge($defaultClientOptions, $this->clientOptions);
        
        // Configuração do textarea HTML
        $this->options = ArrayHelper::merge(['rows' => 6], $this->options);

        // Define a linguagem para o Asset Bundle do componente pai
        $this->language = 'pt_BR';

        parent::init();
    }
}