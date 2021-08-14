<?php

namespace app\models;

use yii\base\Model;

class AdvertisementEmailForm extends Model
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
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'name' => 'Dein Name',
            'email' => 'Deine E-Mail',
            'verifyCode' => ' Prüfcode'
        );
    }

    /**
     * @return array
     */
    public function rules()
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
