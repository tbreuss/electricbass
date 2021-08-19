<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\BlogSearch $model
 * @var yii\widgets\ActiveForm $form
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="blog-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'categoryId') ?>

    <?= $form->field($model, 'countryCode') ?>

    <?= $form->field($model, 'language') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'mainTag') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'movedTo') ?>

    <?php // echo $form->field($model, 'subtitle') ?>

    <?php // echo $form->field($model, 'abstract') ?>

    <?php // echo $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'renderer') ?>

    <?php // echo $form->field($model, 'fotoCopyright') ?>

    <?php // echo $form->field($model, 'hideFoto') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'ratings') ?>

    <?php // echo $form->field($model, 'ratingAvg') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'featured') ?>

    <?php // echo $form->field($model, 'publication') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
