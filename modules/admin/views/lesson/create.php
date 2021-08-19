<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Lesson $model
 */

use yii\helpers\Html;

$this->title = 'Lektion hinzufügen';
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
