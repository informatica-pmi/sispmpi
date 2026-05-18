<?php

namespace app\models;

use Yii;
use app\modules\elaborar\models\Publicacao;
use app\modules\executar\models\AcaoExecucaoArquivo;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "arquivo".
 *
 * @property int $id
 * @property string $nome_original
 * @property string $nome_servidor
 * @property float $tamanho
 * @property string $extensao
 * @property string $path
 * @property string $token
 *
 * @property AcaoExecucaoArquivo[] $acaoExecucaoArquivos
 * @property Publicacao[] $publicacaos
 * @property Publicacao[] $publicacaos0
 * @property Publicacao[] $publicacaos1
 * @property Publicacao[] $publicacaos2
 * @property string $generateToken
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
            [['nome_original', 'nome_servidor', 'tamanho', 'extensao', 'path', 'token'], 'required'],
            [['tamanho'], 'number'],
            [['token'], 'string', 'max' => 32],
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
            'token' => 'Token',
        ];
    }

    /**
     * @throws \yii\base\Exception
     */
    public function upload($folder, ?int $id = null, string $path = '')
    {
        $baseUpload = empty($path) && empty($id)
            ? Yii::getAlias("$folder")
            : Yii::getAlias("$folder/$id/$path");

        $basePath = Yii::getAlias('@app/' . $baseUpload);
        $arquivo = $this->file;

        if (!file_exists($basePath)) {
            FileHelper::createDirectory($basePath);
        }

        $nomeServidor = Yii::$app->security->generateRandomString() . '.' . $arquivo->extension;

        if ($arquivo->saveAs($basePath . '/' . $nomeServidor)) {
            $modelArquivo = new Arquivo();
            $modelArquivo->nome_original = $arquivo->baseName;
            $modelArquivo->nome_servidor = $nomeServidor;
            $modelArquivo->tamanho = $arquivo->size;
            $modelArquivo->extensao = $arquivo->extension;
            $modelArquivo->path = $baseUpload . '/' . $nomeServidor;
            $modelArquivo->token = static::generateToken();

            if ($modelArquivo->save(false)) {
                return $modelArquivo->id;
            }
        }

        return false;
    }

    /**
     * @throws \yii\base\Exception
     */
    public function uploads($folder, ?int $id = null, string $path = '')
    {
        if ($this->validate(['files'])) {
            $baseUpload = empty($path) && empty($id)
                ? Yii::getAlias("$folder")
                : Yii::getAlias("$folder/$id/$path");

            $basePath = Yii::getAlias('@app/' . $baseUpload);

            if (!file_exists($basePath)) {
                FileHelper::createDirectory($basePath);
            }

            $arquivoIds = [];

            foreach ($this->files as $arquivo) {
                $nomeServidor = Yii::$app->security->generateRandomString() . '.' . $arquivo->extension;

                if ($arquivo->saveAs($basePath . '/' . $nomeServidor)) {
                    $modelArquivo = new Arquivo();
                    $modelArquivo->nome_original = $arquivo->baseName;
                    $modelArquivo->nome_servidor = $nomeServidor;
                    $modelArquivo->tamanho = $arquivo->size;
                    $modelArquivo->extensao = $arquivo->extension;
                    $modelArquivo->path = $baseUpload . '/' . $nomeServidor;
                    $modelArquivo->token = static::generateToken();

                    if ($modelArquivo->save(false)) {
                        $arquivoIds[] = $modelArquivo->id;
                    }
                }
            }

            return $arquivoIds;
        }

        return false;
    }

    /**
     * @throws \yii\base\Exception
     */
    public static function generateToken(int $length = 32): string
    {
        do {
            $token = Yii::$app->security->generateRandomString($length);

            $exists = static::find()->where(['token' => $token])->exists();
        } while ($exists);

        return $token;
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
