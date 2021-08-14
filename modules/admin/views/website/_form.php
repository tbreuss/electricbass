<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Website */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="website-form">

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'countryCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'comments')->textInput() ?>

    <?php // $form->field($model, 'ratings')->textInput() ?>

    <?php // $form->field($model, 'ratingAvg')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'hits')->textInput() ?>

    <?php // $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'geocodingAddress')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <div>
        <p>Screenshot in fixer Grösse 4:3 erstellen mit:</p>
        <pre>chrome --headless --disable-gpu --screenshot --window-size=1280,960 <?= $model->website ?></pre>
    </div>
    <?php ActiveForm::end(); ?>

</div>
