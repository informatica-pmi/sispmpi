<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Status;
use app\components\helpers\Mail;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => Status::STATUS_ATIVO],
                'message' => 'Não há usuário com este endereço de e-mail.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => Status::STATUS_ATIVO,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(false)) {
                return false;
            }
        }

        $prepareParams = [
            'nome' => $user->nome,
            'resetLink' => Yii::$app->urlManager->createAbsoluteUrl([
                '/site/reset-password',
                'token' => $user->password_reset_token
            ])
        ];

        Mail::send('passwordResetToken', $prepareParams, $this->email, 'Redefinição de senha');

        return true;
    }
}
