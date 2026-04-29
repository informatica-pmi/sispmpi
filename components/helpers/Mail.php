<?php

namespace app\components\helpers;

use app\models\User;
use Yii;
use app\tasks\MailJob;

class Mail
{
    /**
     * Enviando um unico e-mail
     * @param string $view
     * @param array|null $params
     * @param string $to
     * @param string $title
     */
    public static function send($view, $params, $to, $title)
    {
        $emailControlador = Yii::$app->params['emailControlador'];

        if ($emailControlador != $to) {
            Yii::$app->queue->push(new MailJob([
                'view' => $view,
                'params' => $params,
                'to' => $to,
                'title' => $title,
            ]));
        }
    }

    /**
     * Enviando varios e-mails
     * @param string $view
     * @param array|null $params
     * @param array $users
     * @param string $title
     */
    public static function sendMultiple($view, $params, $users, $title)
    {
        $emailControlador = Yii::$app->params['emailControlador'];
        $nomeOrgao = User::getIdentidade('orgao', 'nome');

        foreach ($users as $user) {
            if (is_null($params) || !is_null($params) && isset($cleanParams)) {
                $params = [
                    'nome' => $user->nome,
                    'nomeOrgao' => $nomeOrgao
                ];

                $cleanParams = true;
            }

            if ($emailControlador != $user->email) {
                Yii::$app->queue->push(new MailJob([
                    'view' => $view,
                    'params' => $params,
                    'to' => $user->email,
                    'title' => $title,
                ]));
            }
        }
    }
}
