<?php

/**
 * @var app\models\Comment[] $models
 */

use app\helpers\Html;

$count = count($models);
if ($count == 0) return;
?>

<div class="comments__list">

    <h2 class="comments__listTitle"><?= $count ?> Kommentare</h2>

    <?php foreach ($models as $i => $model): ?>
        <?php if (!empty($i)): ?>
            <hr><?php endif; ?>
        <div class="comments__item">
            <p class="comments__itemDetails">von <?= empty($model->website) ? Html::encode($model->name) : Html::a(Html::encode($model->name), Html::encode($model->website), ["target" => "_blank", "rel" => "nofollow"]) ?>
                am <?= Yii::$app->formatter->asDate(Html::encode($model->created), "long") ?>
                um <?= Yii::$app->formatter->asTime(Html::encode($model->created), "short") ?> Uhr
            </p>
            <p class="comments__itemText"><?= nl2br(Html::encode(trim($model->text))) ?></p>
        </div>
    <?php endforeach; ?>

</div>
