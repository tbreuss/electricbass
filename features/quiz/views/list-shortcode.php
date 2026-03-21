<?php

/**
 * @var app\features\quiz\models\Quiz[] $models
 * @var yii\web\View $this
 */
?>
<h1>Noten lesen</h1>

<div class="row gy-4 exercises">
    <?php foreach ($models as $index => $model): ?>
        <div class="col-6 col-md-4">
            <div class="exercise">
                <h2><?= $index + 1 ?></h2>
                <p><?= $model->title ?></p>
                <a class="btn btn-primary" href="/quiz/<?= $model->uid ?>">Anfangen</a>
            </div>
        </div>
    <?php endforeach ?>
</div>

<style>
    .exercise {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 1rem;
    }
    .exercise h2 {
        margin-bottom: 0;
    }
    .exercise p {
        margin-bottom: 1rem;
    }
</style>
