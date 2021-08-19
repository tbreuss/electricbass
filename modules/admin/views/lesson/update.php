<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Lesson $model
 */

use yii\helpers\Html;

$this->title = 'Lektion aktualisieren: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aktualisieren';
?>
<div class="lesson-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
