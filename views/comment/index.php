<?php

/**
 * @var yii\web\View $this
 * @var app\models\Comment $model
 * @var string $title
 * @var string $url
 */

use app\helpers\Html;
use app\helpers\Url;

$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
$this->title = 'Kommentar zum Artikel';
?>

<?php if (Yii::$app->session->hasFlash('comment/success')): ?>
    <h2><?= urldecode($title) ?></h2>
    <div class="flash flash--success col-12">
        <?= Yii::$app->session->getFlash('comment/success') ?>
    </div>
    <p><a href="<?= urldecode($url) ?>">Zurück zum Artikel</a></p>
    <?php return ?>
<?php endif; ?>

<div class="comments__form">
    <h2 style="margin-bottom:0">Kommentar zum Artikel</h2>
    <p><a href="<?= urldecode($url) ?>" style="color: #5F5C52; text-decoration: underline"><?= urldecode($title) ?></a></p>

    <?php /* if (Yii::$app->session->hasFlash('comment/error')): ?>
        <div class="flash flash--danger col-12">
            <?= Yii::$app->session->getFlash('comment/error') ?>
        </div>
    <?php endif; */ ?>

    <?= Html::beginForm(['/comment/index', 'name' => $model->tableName, 'id' => $model->tableId], 'post', ['up-layer' => 'current', 'id' => 'form', 'class' => 'form', 'novalidate' => true]) ?>

    <div class="row">
        <div class="col-12 form__row">
            <?php $errorClass = $model->hasErrors('text') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('text'), 'text', ['class' => 'form__label']); ?>
            <?= Html::textarea('Comment[text]', $model->text, ['rows' => 6, 'class' => 'form__textarea form-control ' . $errorClass]); ?>
            <?= Html::error($model, 'text', ['class' => 'invalid-feedback']); ?>
            <div class="form__hint">max. 1000 Zeichen</div>
        </div>

        <div class="col-12 form__row">
            <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label']); ?>
            <?= Html::textInput('Comment[name]', $model->name, ['class' => 'form__textInput form-control ' . $errorClass]); ?>
            <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 form__row">
            <?php $errorClass = $model->hasErrors('check') ? 'is-invalid' : ''; ?>
            <div class="form__checkboxWrapper">
                <?= Html::checkbox('Comment[check]', $model->check, ['id' => 'check', 'class' => 'form__checkbox ' . $errorClass]); ?>
                <?= Html::label('Ich habe die <a up-layer="new" href="' . Url::to(['/comment/rules']) . '">Kommentarregeln</a> gelesen und erkläre mich einverstanden.', 'check', ['class' => 'form__labelCheckbox']); ?>
            </div>
            <?= Html::error($model, 'check', ['class' => 'invalid-feedback']); ?>
        </div>

        <?php /*<div class="col-sm-10 form__row">
            <?php $errorClass = $model->hasErrors('verifyCode') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('verifyCode'), 'verifyCode', ['class' => 'form__label is-required']); ?>
            <?= Captcha::widget(['name' => 'Comment[verifyCode]', 'options' => ['class' => 'form__textInput ' . $errorClass]]) ?>
            <?= Html::error($model, 'verifyCode', ['class' => 'invalid-feedback']); ?>
        </div>*/ ?>

        <div class="col-6 form__row form__row--buttons" style="margin-top: 0.5rem">
            <?= Html::submitButton('Kommentar absenden', ['class' => 'form__submit']) ?>
        </div>

    </div>
    <?= Html::endForm() ?>

</div>
