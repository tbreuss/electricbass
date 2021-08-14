<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'eid')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'oid')->textInput() ?>

    <?= $form->field($model, 'countryCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'platform')->dropDownList([ 'youtube' => 'Youtube', 'vimeo' => 'Vimeo', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'width')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'height')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true])->hint('EID "' . $model->eid . '" wird hinten angehängt') ?>

    <?= $form->field($model, 'abstract')->textarea(['maxlength' => true, 'rows' => 3]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'comments')->textInput() ?>

    <?php // $form->field($model, 'ratings')->textInput() ?>

    <?php // $form->field($model, 'ratingAvg')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'hits')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
