<?php

/**
 * @var app\models\AdvertisementEmailForm $model
 * @var yii\web\View $this
 */

use app\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Inserate verwalten | Bassmarkt';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserate verwalten';

?>

<div class="content">

    <h1>Inserate verwalten</h1>

    <?php if (Yii::$app->session->hasFlash('manageFormSubmitted')): ?>
        <p>Wir haben an die angegebene E-Mail-Adresse eine E-Mail mit den aktiven Inseraten gesendet.</p>
        <p>Hinweis: Die E-Mail kann im Spam-Ordner deines Mailprogramms gelandet sein. Falls du keine E-Mail bekommen hast, melde dich bei uns über das Kontaktformular.</p>
        <p><?php echo Html::a('Zurück zu den Inseraten', ['advertisement/index']) ?>

    <?php else: ?>
        <p>Wir schicken dir einen Link, mit dem du deine Inserate verwalten kannst. Gib dafür deine E-Mail-Adresse ein.</p>

        <?= Html::beginForm('#form', 'post', ['id' => 'form', 'class' => 'form', 'novalidate' => true]) ?>

        <div class="row">
                <div class="col-sm-10 form__row form__row--name">
                    <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
                    <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label is-required']); ?>
                    <?= Html::textInput('AdvertisementEmailForm[name]', $model->name, ['class' => 'form__textInput ' . $errorClass, 'autofocus' => true]); ?>
                    <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
                </div>

                <div class="col-sm-10 form__row">
                    <?php $errorClass = $model->hasErrors('email') ? 'is-invalid' : ''; ?>
                    <?= Html::label($model->getAttributeLabel('email'), 'email', ['class' => 'form__label is-required']); ?>
                    <?= Html::textInput('AdvertisementEmailForm[email]', $model->email, ['type' => 'email', 'class' => 'form__textInput ' . $errorClass]); ?>
                    <?= Html::error($model, 'email', ['class' => 'invalid-feedback']); ?>
                </div>

                <div class="col-sm-10 form__row">
                    <?php $errorClass = $model->hasErrors('verifyCode') ? 'is-invalid' : ''; ?>
                    <?= Html::label($model->getAttributeLabel('verifyCode'), 'verifyCode', ['class' => 'form__label is-required']); ?>
                    <?= Captcha::widget(['name' => 'AdvertisementEmailForm[verifyCode]', 'options' => ['class' => 'form__textInput ' . $errorClass]]) ?>
                    <?= Html::error($model, 'verifyCode', ['class' => 'invalid-feedback']); ?>
                </div>

                <div class="col-sm-5 form__row form__row--buttons">
                    <?= Html::submitButton('Absenden', ['class' => 'form__submit']) ?>
                </div>

                <div class="col-sm-5 form__row form__row--buttons">
                <?php echo Html::a('Abbrechen', ['advertisement/index'], ['class' => 'form__cancel']) ?>
                </div>

        </div>
        <?= Html::endForm() ?>

    <?php endif; ?>
</div>
