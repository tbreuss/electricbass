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
        return [
            'name' => 'Dein Name',
            'email' => 'Deine E-Mail-Adresse',
            'phone' => 'Deine Telefonnummer',
            'message' => 'Mitteilung an Anbieter',
            'nspm' => 'NSPM',
            'verifyCode' => ' Prüfcode'
        ];
    }

    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            // name
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            // email
            ['email', 'required'],
            ['email', 'filter', 'filter' => 'strip_tags'],
            ['email', 'email'],
            // phone
            ['phone', 'filter', 'filter' => 'strip_tags'],
            // message
            ['message', 'required'],
            ['message', 'filter', 'filter' => 'strip_tags'],
            // nspm
            ['nspm', 'safe'],
            // verifyCode
            ['verifyCode', 'captcha'],
        ];
    }
}
