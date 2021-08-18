<?php
/**
 * @var Advertisement $advertisement
 * @var AdvertisementContactForm $model
 * @var View $this
 */

use app\helpers\Html;
use app\models\Advertisement;
use app\models\AdvertisementContactForm;
use yii\captcha\Captcha;
use yii\web\View;

$this->title = 'Anbieter kontaktieren';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = ['label' => $advertisement->title, 'url' => $advertisement->url];
$this->params['breadcrumbs'][] = 'Anbieter kontaktieren';
?>

<div class="content">

    <h1><?php echo $this->title ?></h1>

    <p>
        Inserat: <?php echo stripslashes($advertisement->title) ?><br>
        Name: <?php echo $advertisement->name ?>
    </p>

    <?= Html::beginForm('#form', 'post', ['id' => 'form', 'class' => 'form', 'novalidate' => true]) ?>

    <div class="row">
    <div class="col-sm-10 form__row">
        <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label is-required']); ?>
        <?= Html::textInput('AdvertisementContactForm[name]', $model->name, ['class' => 'form__textInput ' . $errorClass, 'autofocus' => true]); ?>
        <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-sm-10 form__row">
        <?php $errorClass = $model->hasErrors('email') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('email'), 'email', ['class' => 'form__label is-required']); ?>
        <?= Html::textInput('AdvertisementContactForm[email]', $model->email, ['type' => 'email', 'class' => 'form__textInput ' . $errorClass]); ?>
        <?= Html::error($model, 'email', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-sm-10 form__row">
        <?php $errorClass = $model->hasErrors('phone') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('phone'), 'phone', ['class' => 'form__label']); ?>
        <?= Html::textInput('AdvertisementContactForm[phone]', $model->phone, ['type' => 'tel', 'class' => 'form__textInput ' . $errorClass, 'autofocus' => true]); ?>
        <?= Html::error($model, 'phone', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-sm-12 form__row">
        <?php $errorClass = $model->hasErrors('message') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('message'), 'message', ['class' => 'form__label is-required']); ?>
        <?= Html::textarea('AdvertisementContactForm[message]', $model->message, ['rows' => 6, 'class' => 'form__textarea ' . $errorClass]); ?>
        <?= Html::error($model, 'message', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-sm-10 form__row form__row--nspm">
        <?php $errorClass = $model->hasErrors('nspm') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('nspm'), 'nspm', ['class' => 'form__label is-required']); ?>
        <?= Html::textInput('AdvertisementContactForm[nspm]', $model->nspm, ['class' => 'form__textInput ' . $errorClass, 'autofocus' => true]); ?>
        <?= Html::error($model, 'nspm', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-sm-10 form__row">
        <?php $errorClass = $model->hasErrors('verifyCode') ? 'is-invalid' : ''; ?>
        <?= Html::label($model->getAttributeLabel('verifyCode'), 'verifyCode', ['class' => 'form__label is-required']); ?>
        <?= Captcha::widget(['name' => 'AdvertisementContactForm[verifyCode]', 'options' => ['class' => 'form__textInput ' . $errorClass]]) ?>
        <?= Html::error($model, 'verifyCode', ['class' => 'invalid-feedback']); ?>
    </div>

    <div class="col-5 form__row form__row--buttons">
        <?= Html::submitButton('Absenden', ['class' => 'form__submit']) ?>
    </div>

    <div class="col-5 form__row form__row--buttons">
        <?php echo Html::a('Zurück zum Inserat', $advertisement->url, array('class' => 'form__cancel')) ?>
    </div>

    </div>

    <?= Html::endForm() ?>

</div>
