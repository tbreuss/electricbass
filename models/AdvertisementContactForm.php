<?php

namespace app\models;

use yii\base\Model;

class AdvertisementContactForm extends Model
{
	public $name;
	public $email;
    public $phone;
	public $message;
	public $nspm;
    public $verifyCode;

    public function attributeLabels()
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
	 * Declares the validation rules.
	 */
	public function rules()
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