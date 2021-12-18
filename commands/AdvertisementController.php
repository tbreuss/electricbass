<?php

namespace app\commands;

use app\models\Advertisement;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

final class AdvertisementController extends Controller
{
    /**
     * Reminder Action
     */
    public function actionReminder(): void
    {
        $message = '';

        $models = Advertisement::findAllForReminders();
        foreach ($models as $model) {
            $this->sendReminderMail($model);
            $model->reminded = new Expression('NOW()');
            $model->save(false, ['reminded']);
            $message .= "{$model->title} ({$model->id})\n";
        }

        if (!empty($message)) {
            $this->sendAdminMail('Erinnerungen gesendet | electricbass.ch', $message);
        }
    }

    protected function sendAdminMail(string $subject, string $textBody): bool
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

    public function sendReminderMail(Advertisement $model): bool
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
        /* @phpstan-ignore-next-line */
        $message->getSwiftMessage()->getHeaders()
            ->addPathHeader('Return-Path', key(Yii::$app->params['senderEmail']));

        return $message->send();
    }
}
