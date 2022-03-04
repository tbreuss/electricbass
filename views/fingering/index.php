<?php

/**
 * @var yii\web\View $this
 * @var app\models\Fingering[] $models
 */

use app\helpers\Html;

$category = '';

$this->title = 'Fingersätze für E-Bass';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = 'Fingersätze';
?>

<div class="content">

    <h1>Fingersätze für E-Bass</h1>

    <p>Hier findest du E-Bass Fingersätze für die linke Hand für Intervalle, Akkorde und Tonleitern für vier-, fünf- und sechssaitige E-Bässe.</p> 
    <?php foreach ($models as $model): ?>
        <?php if ($category != $model->category): ?>
            <?php if (!empty($category)) {
                echo "</ul>";
            } ?>
            <?php $category = $model->category ?>
            <h2><?= Yii::t('app', $category) ?></h2>
            <ul>
        <?php endif; ?>

        <li><?= Html::a($model->title, $model->url) ?></li>

    <?php endforeach; ?>
    </ul>

</div>
