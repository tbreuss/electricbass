<?php

/**
 * @var app\models\Comment $model
 */

use app\helpers\Html;

?>
<div class="comments__form">

    <?php if (Yii::$app->session->hasFlash('comment/error')): ?>
        <div class="flash flash--danger col-12 col-sm-8">
            <?= Yii::$app->session->getFlash('comment/error') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('comment/success')): ?>
        <div class="flash flash--success col-12 col-sm-8">
            <?= Yii::$app->session->getFlash('comment/success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($count)): ?>
        <h2 class="comments__formTitle">Schreib den ersten Kommentar.</h2>
    <?php else: ?>
        <h2 class="comments__formTitle">Diskutiere mit.</h2>
    <?php endif; ?>

    <?= Html::beginForm('#form', 'post', ['id' => 'form', 'class' => 'form', 'novalidate' => true]) ?>

    <div class="row">
        <div class="col-12 col-sm-8 form__row">
            <?php $errorClass = $model->hasErrors('text') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('text'), 'text', ['class' => 'form__label']); ?>
            <?= Html::textarea('Comment[text]', $model->text, ['rows' => 6, 'class' => 'form__textarea form-control ' . $errorClass]); ?>
            <?= Html::error($model, 'text', ['class' => 'invalid-feedback']); ?>
            <div class="form__hint">max. 1000 Zeichen</div>
        </div>

        <div class="col-12 col-sm-8 form__row">
            <?php $errorClass = $model->hasErrors('name') ? 'is-invalid' : ''; ?>
            <?= Html::label($model->getAttributeLabel('name'), 'name', ['class' => 'form__label']); ?>
            <?= Html::textInput('Comment[name]', $model->name, ['class' => 'form__textInput form-control ' . $errorClass]); ?>
            <?= Html::error($model, 'name', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 col-sm-8 form__row">
            <?php $errorClass = $model->hasErrors('check') ? 'is-invalid' : ''; ?>
            <div class="form__checkboxWrapper">
                <?= Html::checkbox('Comment[check]', $model->check, ['id' => 'check', 'class' => 'form__checkbox ' . $errorClass]); ?>
                <?= Html::label('Ich habe die <a role="button" data-collapsible="#comment-rules" href="#">Kommentarregeln</a> gelesen und erkläre mich einverstanden.', 'check', ['class' => 'form__labelCheckbox']); ?>
            </div>
            <?= Html::error($model, 'check', ['class' => 'invalid-feedback']); ?>
        </div>

        <div class="col-12 col-sm-8 form__row collapsible comments__rules" id="comment-rules">
            <p><b>Kommentarregeln</b></p>
            <p><i>Ehrverletzung/Beleidigung</i>: Um einen angenehmen, sachlichen und fairen Umgang miteinander zu gewährleisten, publizieren wir keine Beiträge, die sich im Ton vergreifen. Dazu gehören die Verwendung von polemischen und beleidigenden Ausdrücken ebenso wie persönliche Angriffe auf andere Diskussionsteilnehmer.</p>
            <p><i>Rassismus/Diskriminierung</i>: Es ist nicht erlaubt, Inhalte zu verbreiten, die unter die Schweizerische Rassismusstrafnorm fallen und Personen aufgrund ihrer Rasse, Ethnie oder Religion herabsetzen oder zu Hass aufrufen. Diskriminierende Äusserungen werden nicht publiziert.</p>
            <p><i>Anschuldigung/Verleumdung</i>: Wir dulden keine Anschuldigungen gegen einzelne Personen oder Unternehmen.</p>
            <p><i>Vulgarität</i>: Wir publizieren keine Kommentare, die Fluchwörter enthalten oder vulgär sind.</p>
            <p><i>Werbung</i>: Eigenwerbung, Reklame für kommerzielle Produkte oder politische Werbung haben keinen Platz in Onlinekommentaren. Ebenso unzulässig sind Aufrufe zu Protesten, Vereinigungen oder politischen Aktionen.</p>
            <p><i>Links</i>: Beiträge mit Links werden nicht publiziert.</p>
            <p><i>Regeldiskussionen</i>: Die Kommentarspalte ist kein Platz, um über die Kommentarrichtlinien zu verhandeln. Freischaltdiskussionen werden grundsätzlich nicht publiziert.</p>
            <p><i>Generell gilt</i>: Wir sperren Nutzer, die sich wiederholt nicht an diese Regeln halten.</p>
            <p><i>Recht auf Publikation</i>: Bitte habe Verständnis dafür, dass kein Recht auf Publikation von Kommentaren besteht.</p>
        </div>

        <div class="col-6 form__row form__row--buttons">
            <?= Html::submitButton('Kommentar absenden', ['class' => 'form__submit']) ?>
        </div>

    </div>
    <?= Html::endForm() ?>

</div>
