<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
final class ContactForm extends Model
{
    /** @var string */
    public $name;
    /** @var string */
    public $email;
    /** @var string */
    public $subject;
    /** @var string */
    public $body;
    /** @var string */
    public $verifyCode;

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'email' => 'E-Mail',
            'subject' => 'Betreff',
            'body' => 'Nachricht',
            'verifyCode' => ' Prüfcode',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
