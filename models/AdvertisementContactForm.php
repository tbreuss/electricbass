<?php

namespace app\models;

use yii\base\Model;

final class AdvertisementContactForm extends Model
{
    /** @var string */
    public $name;
    /** @var string */
    public $email;
    /** @var string */
    public $phone;
    /** @var string */
    public $message;
    /** @var string */
    public $nspm;
    /** @var string */
    public $verifyCode;

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return array(
            'name' => 'Dein Name',
            'email' => 'Deine E-Mail-Adresse',
            'phone' => 'Deine Telefonnummer',
            'message' => 'Mitteilung an Anbieter',
            'nspm' => 'NSPM',
            'verifyCode' => ' Prüfcode'
        );
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return array(
            // name
            array('name', 'required'),
            array('name', 'filter', 'filter' => 'strip_tags'),
            // email
            array('email', 'required'),
            array('email', 'filter', 'filter' => 'strip_tags'),
            array('email', 'email'),
            // phone
            array('phone', 'filter', 'filter' => 'strip_tags'),
            // message
            array('message', 'required'),
            array('message', 'filter', 'filter' => 'strip_tags'),
            // nspm
            array('nspm', 'safe'),
            // verifyCode
            ['verifyCode', 'captcha'],
        );
    }
}
