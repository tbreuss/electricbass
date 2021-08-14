<?php

namespace app\commands;

use app\models\Advertisement;
use Yii;
use yii\console\Controller;
use yii\db\Expression;


class AdvertisementController extends Controller
{

    /**
     * Reminder Action
     */
    public function actionReminder()
    {
        $message = '';

        $models = Advertisement::findAllForReminders();
        foreach ($models AS $model) {
            $this->sendReminderMail($model);
            $model->reminded = new Expression('NOW()');
            $model->save(false, ['reminded']);
            $message .= "{$model->title} ({$model->id})\n";
        }

        if (!empty($message)) {
            $this->sendAdminMail('Erinnerungen gesendet | electricbass.ch', $message);
        }
    }

    /**
     * @param string $subject
     * @param string $textBody
     * @return bool
     */
    protected function sendAdminMail($subject, $textBody)
    {
        $to = Yii::$app->params['adminEmail'];
        $from = Yii::$app->params['senderEmail'];
        return Yii::$app->mailer
            ->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($textBody)
            ->send();
    }

    /**
     * @param Advertisement $model
     * @return bool
     */
    public function sendReminderMail(Advertisement $model)
    {
        $message = Yii::$app->mailer->compose([
            'html' => 'advertisement/reminder_html',
            'text' => 'advertisement/reminder_text',
        ], [
            'model' => $model
        ]);
        $message->setFrom(Yii::$app->params['senderEmail']);
        $message->setTo($model->email);
        $message->setSubject('Dein Inserat verlängern: ' . $model->title);

        // Add Return-Path
        // @todo Return-Path wird nicht hinzugefügt
        reset(Yii::$app->params['senderEmail']);
        $message->getSwiftMessage()->getHeaders()
            ->addPathHeader('Return-Path', key(Yii::$app->params['senderEmail']));

        return $message->send();
    }

}
