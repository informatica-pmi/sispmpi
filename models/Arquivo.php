<?php

namespace app\models;

use Yii;
use app\modules\elaborar\models\Publicacao;
use app\modules\executar\models\AcaoExecucaoArquivo;

/**
 * This is the model class for table "arquivo".
 *
 * @property int $id
 * @property string $nome_original
 * @property string $nome_servidor
 * @property float $tamanho
 * @property string $extensao
 * @property string $path
 *
 * @property AcaoExecucaoArquivo[] $acaoExecucaoArquivos
 * @property Publicacao[] $publicacaos
 * @property Publicacao[] $publicacaos0
 * @property Publicacao[] $publicacaos1
 * @property Publicacao[] $publicacaos2
 */
class Arquivo extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var UploadedFile[]
     */
    public $files;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arquivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome_original', 'nome_servidor', 'tamanho', 'extensao', 'path'], 'required'],
            [['tamanho'], 'number'],
            [['nome_original', 'nome_servidor', 'path'], 'string', 'max' => 255],
            [['extensao'], 'string', 'max' => 20],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['pdf', 'png', 'jpg', 'xls', 'xlsx', 'doc', 'docx']],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => ['pdf', 'png', 'jpg', 'xls', 'xlsx', 'doc', 'docx'], 'maxFiles' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome_original' => 'Nome Original',
            'nome_servidor' => 'Nome Servidor',
            'tamanho' => 'Tamanho',
            'extensao' => 'Extensao',
            'path' => 'Path',
        ];
    }

     /**
     * salvando arquivo
     * @param $folder
     * @param $id
     * @param string $path
     * @return bool|int
     * @throws \yii\base\Exception
     */
    public function upload($folder, $id = '', $path = '')
    {
        $baseUpload = empty($path) && empty($id) ? Yii::getAlias("{$folder}") : Yii::getAlias("{$folder}/$id/{$path}");
        $basePath = Yii::getAlias('@webroot/' . $baseUpload);
        $arquivo = $this->file;

        if (!file_exists($basePath)) {
            mkdir($basePath, 0775, true);
        }

        $nomeDb = Yii::$app->security->generateRandomString() . '.' . $arquivo->extension;
        $fullPath = $baseUpload . '/' . $nomeDb;

        $arquivo->saveAs($fullPath);
        $modelArquivo = new Arquivo();
        $modelArquivo->nome_original = $arquivo->baseName;
        $modelArquivo->nome_servidor = $nomeDb;
        $modelArquivo->tamanho = $arquivo->size;
        $modelArquivo->extensao = $arquivo->extension;
        $modelArquivo->path = $fullPath;
        $modelArquivo->save(false);
        return $modelArquivo->id;
    }

    public function uploads($folder, $id = '', $path = '')
    {
        if ($this->validate(['files'])) {
            $baseUpload = empty($path) && empty($id) ?
                Yii::getAlias("{$folder}") :
                Yii::getAlias("{$folder}/$id/{$path}");

            $basePath = Yii::getAlias('@webroot/' . $baseUpload);

            if (!file_exists($basePath)) {
                mkdir($basePath, 0775, true);
            }

            $arquivoIds = [];

            foreach ($this->files as $arquivo) {
                $nomeDb = Yii::$app->security->generateRandomString() . '.' . $arquivo->extension;
                $fullPath = $baseUpload . '/' . $nomeDb;

                $arquivo->saveAs($fullPath);
                $modelArquivo = new Arquivo();
                $modelArquivo->nome_original = $arquivo->baseName;
                $modelArquivo->nome_servidor = $nomeDb;
                $modelArquivo->tamanho = $arquivo->size;
                $modelArquivo->extensao = $arquivo->extension;
                $modelArquivo->path = $fullPath;
                $modelArquivo->save(false);
                $arquivoIds[] = $modelArquivo->id;
            }

            return $arquivoIds;
        }

        return false;
    }

    /**
     * Gets query for [[AcaoExecucaoArquivos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcaoExecucaoArquivos()
    {
        return $this->hasMany(AcaoExecucaoArquivo::className(), ['arquivo_id' => 'id']);
    }

    /**
     * Gets query for [[Publicacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicacaos()
    {
        return $this->hasMany(Publicacao::className(), ['plano_acao_arquivo' => 'id']);
    }

    /**
     * Gets query for [[Publicacaos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicacaos0()
    {
        return $this->hasMany(Publicacao::className(), ['plano_comunicacao_arquivo' => 'id']);
    }

    /**
     * Gets query for [[Publicacaos1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicacaos1()
    {
        return $this->hasMany(Publicacao::className(), ['plano_integridade_arquivo' => 'id']);
    }

    /**
     * Gets query for [[Publicacaos2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublicacaos2()
    {
        return $this->hasMany(Publicacao::className(), ['plano_treinamento_arquivo' => 'id']);
    }
}
