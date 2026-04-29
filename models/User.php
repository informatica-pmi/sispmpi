<?php

namespace app\models;

use Yii;
use yii\web\JsExpression;
use app\modules\admin\models\Orgao;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $masp
 * @property string $login
 * @property string $senha
 * @property string|null $cargo
 * @property string $email
 * @property string|null $telefone
 * @property int $orgao_id
 * @property int|null $unidade_administrativa_id
 * @property int $status
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int|null $cadastrado_por
 * @property string $created_at
 *
 * @property Orgao $orgao
 * @property UnidadeAdministrativa $unidadeAdministrativa
 * @property User $cadastradoPor
 * @property User[] $usuarios
 * @property AuthAssignment $authAssignment
 * @property AuthAssignment[] $authAssignments
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public const PERFIL_TI = 'TI';
    public const PERFIL_ADMINISTRADOR = 'Administrador';
    public const PERFIL_AUDITOR = 'Auditor';
    public const PERFIL_GRUPO_TRABALHO = 'Grupo de Trabalho';
    public const PERFIL_EXECUTOR = 'Executor';
    public const PERFIL_MONITORAMENTO = 'Monitoramento';
    public const PERFIL_ALTA_ADMINISTRACAO = 'Alta Administração';
    public const PERFIL_OBSERVADOR = 'Observador';

    public const SCENARIO_CGE = 'cge';
    public const SCENARIO_AUDITOR = 'auditor';
    public const SCENARIO_OUTRO = 'outro';
    public const SCENARIO_UPDATE = 'update';

    public $perfil;

    public $perfis;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'login', 'email', 'status'], 'required'],
            [['cargo', 'telefone', 'perfis'], 'required', 'on' => [self::SCENARIO_CGE, self::SCENARIO_UPDATE]],
            [['masp'], 'required', 'on' => [self::SCENARIO_CGE, self::SCENARIO_UPDATE, self::SCENARIO_OUTRO]],
            [['orgao_id'], 'required', 'on' => [self::SCENARIO_AUDITOR, self::SCENARIO_UPDATE]],
            [['perfil'], 'required', 'on' => self::SCENARIO_OUTRO],
            [['orgao_id', 'status', 'cadastrado_por', 'unidade_administrativa_id'], 'integer'],
            [['created_at'], 'safe'],
            [['email'], 'email'],
            [['nome', 'masp', 'login', 'senha', 'cargo', 'email', 'password_reset_token'], 'string', 'max' => 255],
            [['login'], 'unique'],
            [['telefone'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['masp', 'cargo', 'telefone', 'auth_key', 'password_reset_token'], 'default', 'value' => null],
            [['orgao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgao::className(), 'targetAttribute' => ['orgao_id' => 'id']],
            [['unidade_administrativa_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadeAdministrativa::className(), 'targetAttribute' => ['unidade_administrativa_id' => 'id']],
            [['cadastrado_por'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cadastrado_por' => 'id']],

            [['unidade_administrativa_id'], 'required', 'when' => function ($model) {
                return $model->perfil === User::PERFIL_EXECUTOR;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return $('#user-perfil').val() === '" . User::PERFIL_EXECUTOR . "'
            }")],

            [['orgao_id'], 'required', 'when' => function ($model) {
                return static::getPerfil() === self::PERFIL_ADMINISTRADOR;
            }, 'whenClient' => new JsExpression("function(attribute, value) {
                return '" . static::getPerfil() . "' === 'Administrador';
            }"), 'on' => self::SCENARIO_OUTRO],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CGE] = ['nome', 'masp', 'login', 'cargo', 'email', 'telefone', 'status', 'perfis'];
        $scenarios[self::SCENARIO_AUDITOR] = ['nome', 'login', 'orgao_id', 'email', 'status'];
        $scenarios[self::SCENARIO_OUTRO] = ['nome', 'masp', 'login', 'orgao_id', 'unidade_administrativa_id', 'cargo', 'email', 'status', 'telefone', 'perfil'];
        $scenarios[self::SCENARIO_UPDATE] = ['nome', 'login', 'senha', 'cargo', 'email', 'masp', 'status', 'telefone', 'orgao_id', 'perfis'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'masp' => 'Masp',
            'login' => 'Login',
            'senha' => 'Senha',
            'cargo' => 'Cargo',
            'email' => 'E-mail institucional',
            'telefone' => 'Telefone institucional',
            'orgao_id' => 'Órgão',
            'unidade_administrativa_id' => 'Unidade administrativa',
            'status' => 'Status',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'cadastrado_por' => 'Cadastrado Por',
            'created_at' => 'Created At',
            'perfil' => 'Perfil',
            'perfis' => 'Perfis',
        ];
    }

    /**
     * Array para filtrar na listagem de usuarios perfil CGE
     * @return array
     */
    public static function getFilterCge()
    {
        return [
            self::PERFIL_AUDITOR => self::PERFIL_AUDITOR,
            self::PERFIL_ADMINISTRADOR => self::PERFIL_ADMINISTRADOR,
            self::PERFIL_GRUPO_TRABALHO => self::PERFIL_GRUPO_TRABALHO,
            self::PERFIL_EXECUTOR => self::PERFIL_EXECUTOR,
            self::PERFIL_MONITORAMENTO => self::PERFIL_MONITORAMENTO,
            self::PERFIL_ALTA_ADMINISTRACAO => self::PERFIL_ALTA_ADMINISTRACAO,
            self::PERFIL_OBSERVADOR => self::PERFIL_OBSERVADOR,
        ];
    }

    /**
     * Array para filtrar na listagem de usuarios perfil Auditor
     * @return array
     */
    public static function getFilterAuditor()
    {
        return [
            self::PERFIL_GRUPO_TRABALHO => self::PERFIL_GRUPO_TRABALHO,
            self::PERFIL_EXECUTOR => self::PERFIL_EXECUTOR,
            self::PERFIL_MONITORAMENTO => self::PERFIL_MONITORAMENTO,
            self::PERFIL_ALTA_ADMINISTRACAO => self::PERFIL_ALTA_ADMINISTRACAO,
            self::PERFIL_OBSERVADOR => self::PERFIL_OBSERVADOR,
        ];
    }

    /**
     * Gets query for [[Orgao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgao()
    {
        return $this->hasOne(Orgao::className(), ['id' => 'orgao_id']);
    }

    /**
     * Gets query for [[CadastradoPor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCadastradoPor()
    {
        return $this->hasOne(User::className(), ['id' => 'cadastrado_por']);
    }

    /**
     * Gets query for [[UnidadeAdministrativa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadeAdministrativa()
    {
        return $this->hasOne(UnidadeAdministrativa::className(), ['id' => 'unidade_administrativa_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(User::className(), ['cadastrado_por' => 'id']);
    }

    /**
     * Gets query for [[AuthAssignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id'])->where(['active' => Status::STATUS_SIM]);
    }

    /**
     * Gets query for [[AuthAssignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @param $field string
     * @param $extraField string
     * @return string
     */
    public static function getIdentidade($field, $extraField = null)
    {
        $identity = Yii::$app->user->identity;
        return is_null($extraField) ? $identity->$field : $identity->$field->$extraField;
    }

    /**
     * Retorna perfil do usuário logado
     * @return string
     */
    public static function getPerfil()
    {
        return Yii::$app->user->identity->authAssignment->item_name;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        return $user ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = self::findOne(['login' => $username]);
        return $user ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function generatePassword($password)
    {
        $this->senha = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->senha);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => Status::STATUS_ATIVO,
        ]);
    }
}
