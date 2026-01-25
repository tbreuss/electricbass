<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Lesson $model
 * @var yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="lesson-form">

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'autosort')->textInput() ?>

    <?= $form->field($model, 'navtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'renderer')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'fotos')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'hideFoto')->textInput() ?>

    <?php // $form->field($model, 'comments')->textInput() ?>

    <?php // $form->field($model, 'ratings')->textInput() ?>

    <?php // $form->field($model, 'ratingAvg')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'hits')->textInput() ?>

    <?php // $form->field($model, 'featured')->textInput() ?>

    <?= $form->field($model, 'deleted')->dropDownList([0 => 'Nein', 1 => 'Ja']) ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
