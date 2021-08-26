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
        return [
            'name' => 'Dein Name',
            'email' => 'Deine E-Mail',
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
            ['name', 'safe'],
            // email
            ['email', 'required'],
            ['email', 'string', 'max' => 100, 'encoding' => 'utf-8'],
            ['email', 'email'],
            // verifyCode
            ['verifyCode', 'captcha'],
        ];
    }
}
