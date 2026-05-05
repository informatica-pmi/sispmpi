<?php

namespace app\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Custom TinyMCE widget to standardise configurations across SisPMPI.
 * This class extends the 2amigos TinyMce widget to inject API Keys and default settings.
 */
class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    /**
     * @var string The TinyMCE API Key for Cloud usage.
     */
    public $apiKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        // 1. Prioriza a chave de API (Lê do parâmetro injetado ou do .env diretamente se falhar)
        if (empty($this->apiKey)) {
            $this->apiKey = getenv('TINYMCE_API_KEY');
        }

        // 2. Define configurações padrão de interface (Calibri conforme padrão da CGE-MG)
        $defaultClientOptions = [
            'menubar' => false,
            'statusbar' => false,
            'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify",
            'entity_encoding' => 'raw',
            'paste_as_text' => true,
            'language' => 'pt_BR',
            'content_style' => 'body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }',
            'branding' => false, // Remove a marca d'água "Powered by Tiny"
        ];

        // 3. Mescla as opções padrão com as opções enviadas na View (não sobrescreve)
        $this->clientOptions = ArrayHelper::merge($defaultClientOptions, $this->clientOptions);
        
        // 4. Garante que as linhas (rows) sejam configuradas se não houver definição na View
        $this->options = ArrayHelper::merge(['rows' => 6], $this->options);

        // 5. Chamada vital: Define a linguagem para o componente pai
        $this->language = 'pt_BR';

        parent::init();
    }
}