<?php

/* @var $this yii\web\View */
/* @var $model app\models\ContactForm */

use app\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Kontakt';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = 'onecol';
?>
<div class="content">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('contact/error')): ?>
        <div class="flash flash--danger">
            <?= Yii::$app->session->getFlash('contact/error') ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::$app->session->hasFlash('contact/success')): ?>
        <div class="flash flash--success">
            <?= Yii::$app->session->getFlash('contact/success') ?>
        </div>
    <?php endif; ?>
    <p>
        Hast du Fragen, Wünsche oder Anregungen zur Website, oder gefällt dir etwas nicht?
        Dann füll das Kontaktformular aus und sende mir eine Nachricht.
    </p>


    <?= Html::beginForm('#form', 'post', ['id' => 'form', 'class' => 'form', 'novalidate' => true]) ?>

    <div class="row">

        <div class="col-12 form__row">
            <?php $errorClass = $model->hasErrors('subject') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('subject'), 'subject', ['class' => 'form__label is-required']); ?>
            <?= Html::textInput('ContactForm[subject]', $model->subject, ['class' => 'form__textInput ' . $errorClass]); ?>
            <?= Html::error($model, 'subject', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 form__row">
            <?php $errorClass = $model->hasErrors('body') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('body'), 'body', ['class' => 'form__label is-required']); ?>
            <?= Html::textarea('ContactForm[body]', $model->body, ['rows' => 6, 'class' => 'form__textarea ' . $errorClass]); ?>
            <?= Html::error($model, 'body', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 col-sm-8 col-lg-6 form__row">
            <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label is-required']); ?>
            <?= Html::textInput('ContactForm[name]', $model->name, ['class' => 'form__textInput ' . $errorClass]); ?>
            <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 col-sm-8 col-lg-6 form__row">
            <?php $errorClass = $model->hasErrors('email') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('email'), 'email', ['class' => 'form__label is-required']); ?>
            <?= Html::textInput('ContactForm[email]', $model->email, ['type' => 'email', 'class' => 'form__textInput ' . $errorClass]); ?>
            <?= Html::error($model, 'email', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 col-sm-8 col-lg-6 form__row">
            <?php $errorClass = $model->hasErrors('verifyCode') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('verifyCode'), 'verifyCode', ['class' => 'form__label is-required']); ?>
            <?= Captcha::widget(['name' => 'ContactForm[verifyCode]', 'options' => ['class' => 'form__textInput ' . $errorClass]]) ?>
            <?= Html::error($model, 'verifyCode', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="w-100"></div>

        <div class="col-6 form__row form__row--buttons">
            <?= Html::submitButton('Anfrage absenden', ['class' => 'form__submit']) ?>
        </div>

        <div class="col-6 form__row form__row--buttons">
            <?= Html::a('Abbrechen', ['site/contact'], ['class' => 'form__cancel']) ?>
        </div>

    </div>

    <?= Html::endForm() ?>

</div>
