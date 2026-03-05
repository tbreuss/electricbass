<?php

/**
 * @var yii\web\View $this
 * @var yii\data\Pagination $pagination
 * @var string $sort
 * @var \Joke[] $models
 */

use app\feature\rating\RatingShare;
use app\widgets\CanonicalLink;
use app\widgets\Comments;
use app\widgets\ListSummary;

$this->title = 'Bassistenwitze';
$this->params['breadcrumbs'][] = 'Bassistenwitze';
$this->params['pageTitle'] = 'Die besten Bassistenwitze aller Zeiten';
$this->params['metaDescription'] = 'Die Sammlung mit den besten Bassistenwitzen 😂 aller Zeiten. Mit Witzen über Bassisten oder zum Thema Bass. Jetzt lesen und ablachen.';
CanonicalLink::widget(['isPaginated' => false]);
?>
<div class="content">
    <h1>Bassistenwitze</h1>
    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>
    <div class="widget widget-listview">
        <?php foreach ($models as $i => $model): ?>
            <?php if ($i > 0) {
                echo "<hr>";
            } ?>
            <p><?= nl2br(strip_tags($model->joke)) ?></p>
        <?php endforeach; ?>
    </div>
</div>

<?= RatingShare::widget(["tableName" => "joke", "tableId" => 0]) ?>
<?= Comments::widget(["tableName" => "joke", "tableId" => 0]) ?>
