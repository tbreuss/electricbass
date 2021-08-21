<?php

namespace tests\unit\models;

use app\models\ContactForm;
use yii\mail\MessageInterface;

class ContactFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    public $tester;

    public function testEmailIsSentOnContact()
    {
        $model = new ContactForm();

        $model->attributes = [
            'name' => 'tester name',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
            'verifyCode' => 'testme',
        ];

        verify_that($model->contact('admin@example.com'));

        // using Yii2 module actions to check email was sent
        $this->tester->seeEmailIsSent();

        /** @var MessageInterface $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();

        verify($emailMessage)->instanceOf('yii\swiftmailer\Message');
        verify($emailMessage->getTo())->arrayHasKey('admin@example.com');
        verify($emailMessage->getFrom())->arrayHasKey('tester@example.com');
        verify($emailMessage->getSubject())->equals('very important letter subject');
        verify($emailMessage->toString())->stringContainsString('tester name');
        verify($emailMessage->toString())->stringContainsString('body of current message');
    }
}
