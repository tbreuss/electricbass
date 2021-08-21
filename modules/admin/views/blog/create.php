<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Blog $model
 */

use yii\helpers\Html;

$this->title = 'Blog hinzufügen';
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
