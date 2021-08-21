<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Blog $model
 * @var yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="blog-form">

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'categoryId')->textInput() ?>

    <?= $form->field($model, 'countryCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'mainTag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'movedTo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pageTitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'metaDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'changes')->textarea(['rows' => 3]) ?>

    <?php // $form->field($model, 'renderer')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'fotoCopyright')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'hideFoto')->textInput() ?>

    <?php // $form->field($model, 'comments')->textInput() ?>

    <?php // $form->field($model, 'ratings')->textInput() ?>

    <?php // $form->field($model, 'ratingAvg')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'hits')->textInput() ?>

    <?php // $form->field($model, 'featured')->textInput() ?>

    <?= $form->field($model, 'publication')->textInput() ?>

    <?= $form->field($model, 'created')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
