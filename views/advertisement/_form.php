<?php
use app\helpers\Html;
use app\models\Advertisement;
/** @var $model Advertisement */
?>

<?php if(Yii::$app->session->hasFlash('updateFormSubmitted')): ?>
    <div class="flash flash--success">
        Dein Eintrag wurde aktualisiert.
        <?= Html::a("Inserat anschauen", $model->url, ['target' => '_blank']) ?>
    </div>
<?php endif; ?>

<?= Html::beginForm('#form', 'post', ['id' => 'form', 'class' => 'form', 'novalidate' => true, 'enctype' => 'multipart/form-data']) ?>

<div class="row">

<div class="col-12 form__row">
    <?php $errorClass = $model->hasErrors('category_id') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('category_id'), 'category_id', ['class' => 'form__label is-required']); ?>
    <?= Html::dropDownList('Advertisement[category_id]', $model->category_id, [''=>''] +$model::$categories, ['class' => 'form__dropdown ' . $errorClass, 'autofocus' => true]); ?>
    <?= Html::error($model, 'category_id', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 form__row">
    <?php $errorClass = $model->hasErrors('title') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('title'), 'title', ['class' => 'form__label is-required']); ?>
    <?= Html::textInput('Advertisement[title]', $model->title, ['class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'title', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 form__row">
    <?php $errorClass = $model->hasErrors('longtext') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('longtext'), 'longtext', ['class' => 'form__label is-required']); ?>
    <?= Html::textarea('Advertisement[longtext]', $model->longtext, ['cols' => 50, 'rows' => 8, 'class' => 'form__textarea ' . $errorClass]); ?>
    <?= Html::error($model, 'longtext', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-sm-4 form__row">
    <?php $errorClass = $model->hasErrors('new_price') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('new_price'), 'new_price', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[new_price]', $model->new_price, ['type' => 'number', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'new_price', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-sm-4 form__row">
    <?php $errorClass = $model->hasErrors('sell_price') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('sell_price'), 'sell_price', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[sell_price]', $model->sell_price, ['type' => 'number', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'sell_price', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-sm-4 form__row">
    <?php $errorClass = $model->hasErrors('currency') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('currency'), 'currency', ['class' => 'form__label']); ?>
    <?= Html::dropDownList('Advertisement[currency]', $model->currency, [''=>'','CHF'=>'CHF','EURO'=>'EURO'], ['class' => 'form__dropdown ' . $errorClass]); ?>
    <?= Html::error($model, 'currency', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label is-required']); ?>
    <?= Html::textInput('Advertisement[name]', $model->name, ['class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('phone') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('phone'), 'phone', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[phone]', $model->phone, ['type' => 'tel', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'phone', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('email') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('email'), 'email', ['class' => 'form__label is-required']); ?>
    <?= Html::textInput('Advertisement[email]', $model->email, ['type' => 'email', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'email', ['class' => 'invalid-feedback']); ?>
    <div class="form__hint">Wird nicht veröffentlicht</div>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('homepage') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('homepage'), 'homepage', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[homepage]', $model->homepage, ['type' => 'url', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'homepage', ['class' => 'invalid-feedback']); ?>
    <div class="form__hint">Inklusive http(s)://</div>
</div>

<div id="del" class="col-12 form__row">
    <?php if(($photo = $model->getPhoto()) != ''): ?>
        <div class="form-group field-advertisement-homepage">
            <?php echo Html::img('@web/'.$photo, ['height'=>200]) ?>
            <?php echo Html::submitButton('Foto löschen', array('name' => 'delete', 'class' => 'button button--primary button--small')); ?>
        </div>
    <?php else: ?>
        <?php if (Yii::$app->session->hasFlash('fotoDeleted')): ?>
            <div class="flash flash--success">Das Foto wurde gelöscht.</div>
        <?php endif; ?>
        <?= Html::label($model->getAttributeLabel('upload'), 'upload', ['class' => 'form__label']); ?>
        <?= Html::fileInput('Advertisement[upload]', $model->upload, ['class' => 'form__file ' . $errorClass]); ?>
        <?= Html::error($model, 'upload', ['class' => 'invalid-feedback']); ?>
    <?php endif; ?>
</div>

<div class="col-12 form__row form__row--nspm">
    <?php $errorClass = $model->hasErrors('nspm') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('nspm'), 'nspm', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[nspm]', $model->nspm, ['class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'nspm', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('region') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('region'), 'region', ['class' => 'form__label is-required']); ?>
    <?= Html::textInput('Advertisement[region]', $model->region, ['class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'region', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 col-md-6 form__row">
    <?php $errorClass = $model->hasErrors('country') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('country'), 'country', ['class' => 'form__label is-required']); ?>
    <?= Html::dropDownList('Advertisement[country]', $model->country, [''=>'', 'DE'=>'Deutschland','CH'=>'Schweiz','AT'=>'Österreich'], ['class' => 'form__dropdown ' . $errorClass]); ?>
    <?= Html::error($model, 'country', ['class' => 'invalid-feedback']); ?>
</div>

<div class="col-12 form__row">
    <?php $errorClass = $model->hasErrors('geocodingAddress') ? 'is-invalid' : ''; ?>
    <?= Html::label($model->getAttributeLabel('geocodingAddress'), 'geocodingAddress', ['class' => 'form__label']); ?>
    <?= Html::textInput('Advertisement[geocodingAddress]', $model->geocodingAddress, ['type' => 'url', 'class' => 'form__textInput ' . $errorClass]); ?>
    <?= Html::error($model, 'geocodingAddress', ['class' => 'invalid-feedback']); ?>
    <div class="form__hint">Gib hier eine Adresse passend zum Inserate ein, falls der Standort auf einer Karte erscheinen soll. Bsp: Marktgasse 10, Bern, CH</div>
</div>

<div class="col-6 form__row form__row--buttons">
    <?php $label = $model->isNewRecord ? 'Eintrag hinzufügen' : 'Eintrag aktualisieren' ?>
    <?= Html::submitButton($label, ['name' => 'save', 'class' => 'form__submit']) ?>
</div>

<div class="col-6 form__row form__row--buttons">
    <?php echo Html::a('Zurück zur Übersicht', ['advertisement/index'], ['class' => 'form__cancel']) ?>
</div>

</div>

<?= Html::endForm() ?>
