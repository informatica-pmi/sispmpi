<?php

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Widget TinyMCE customizado para o SisPMPI.
 * Força o carregamento via CDN e define a licença GPL para evitar bloqueios.
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
        // 1. Captura a chave de API (do .env ou params)
        $this->apiKey = $this->apiKey ?: getenv('TINYMCE_API_KEY') ?: (Yii::$app->params['tinyMceApiKey'] ?? null);

        // 2. Configura a URL do script. Se não houver chave, usa 'no-api-key' mas mantém o carregamento via CDN
        $key = ($this->apiKey && $this->apiKey !== 'no-api-key') ? $this->apiKey : 'no-api-key';
        
        // Forçamos o script_url para garantir que o Yii2 NÃO carregue a versão local do vendor/assets
        $this->script_url = "https://cdn.tiny.cloud/1/{$key}/tinymce/6/tinymce.min.js";

        // 3. Configurações de interface e correção de licença
        $defaultClientOptions = [
            'license_key' => 'gpl', // ESSENCIAL: Aceita a licença para remover o bloqueio
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
            // Impede que o TinyMCE procure idiomas localmente se estivermos no CDN
            'language_url' => null, 
            'content_style' => 'body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }'
        ];

        $this->clientOptions = ArrayHelper::merge($defaultClientOptions, $this->clientOptions);
        
        // Garante que o ID do campo seja passado corretamente
        $this->options = ArrayHelper::merge(['rows' => 6], $this->options);

        parent::init();
    }
}