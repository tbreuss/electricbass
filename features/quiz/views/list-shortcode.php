<?php

/**
 * @var app\features\quiz\models\Quiz[] $models
 * @var yii\web\View $this
 */
?>
<div class="row gy-4 exercises">
    <?php foreach ($models as $index => $model): ?>
        <div class="col-6 col-md-4">
            <div class="exercise">
                <h2><?= $index + 1 ?></h2>
                <p><?= $model->title ?></p>
                <a href="/quiz/<?= $model->uid ?>">Anfangen</a>
            </div>
        </div>
    <?php endforeach ?>
</div>

<style>
    .exercise {
        border: 2px solid #d2d4d8;
        border-radius: 10px;
        padding: 1rem;
    }
    .exercise h2 {
        color: #1b1b1b;
        margin: 0;
    }
    .exercise p {
        margin-bottom: 1rem;
        min-height: 3rem;
    }
    .exercise a {
        display: block;
        color: #1b1b1b;
        background-color: #efefef;
        border-color: #d2d4d8;
        border-radius: 9px;
        padding: 7px 9px;
        text-align: center;
        font-weight: normal;
    }
</style>
