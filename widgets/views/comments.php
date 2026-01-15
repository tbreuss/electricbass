<?php

/**
 * @var yii\web\View $this
 * @var app\models\Comment[] $models
 * @var string $tableName
 * @var int $tableId
 * @var int $count
 */
use app\helpers\Html;

?>
<div class="comments__list">
    <h2 class="comments__listTitle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
            <path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>
        </svg>
        <?= $count ?> <?= $count == 1 ? 'Kommentar.' : 'Kommentare.' ?>
        <?php $linkText = empty($count) ? 'Schreib den ersten Kommentar!' : 'Diskutiere mit!' ?>
        <?= app\helpers\Html::a($linkText, ['/comment/index', 'name' => $tableName, 'id' => $tableId], ['up-layer' => 'new', 'up-size' => 'large', 'rel' => 'nofollow']) ?>
    </h2>
    <?php foreach ($models as $i => $model): ?>
        <div class="comments__item">
            <p class="comments__itemDetails">von <?= empty($model->website) ? Html::encode($model->name) : Html::a(Html::encode($model->name), Html::encode($model->website), ["target" => "_blank", "rel" => "nofollow"]) ?>
                am <?= Yii::$app->formatter->asDate(Html::encode($model->created), "long") ?>
                um <?= Yii::$app->formatter->asTime(Html::encode($model->created), "short") ?> Uhr
            </p>
            <p class="comments__itemText"><?= nl2br(Html::encode(trim($model->text))) ?></p>
        </div>
    <?php endforeach; ?>
</div>
