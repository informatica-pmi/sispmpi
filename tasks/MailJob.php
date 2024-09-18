<?php

namespace app\tasks;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class MailJob extends BaseObject implements JobInterface
{
    public $view;
    public $params;
    public $to;
    public $title;

    public function execute($queue)
    {
        Yii::$app->mailer->compose($this->view, ['params' => $this->params])
            ->setTo($this->to)
            ->setSubject($this->title)
            ->send();
    }
}