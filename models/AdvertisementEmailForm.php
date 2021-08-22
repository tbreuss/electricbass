<?php

namespace app\models;

use yii\base\Model;

final class AdvertisementEmailForm extends Model
{
    /**
     * Fake (nur als Spamschutz benötigt)
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $verifyCode;

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return array(
            'name' => 'Dein Name',
            'email' => 'Deine E-Mail',
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
            array('name', 'safe'),
            // email
            array('email', 'required'),
            array('email', 'string', 'max' => 100, 'encoding' => 'utf-8'),
            array('email', 'email'),
            // verifyCode
            ['verifyCode', 'captcha'],
        );
    }

}
