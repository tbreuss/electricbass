<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\WebsiteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="website-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'countryCode') ?>

    <?= $form->field($model, 'language') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'subtitle') ?>

    <?php // echo $form->field($model, 'abstract') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'ratings') ?>

    <?php // echo $form->field($model, 'ratingAvg') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'geocodingAddress') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
