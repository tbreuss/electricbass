<?php

/**
 * @var yii\web\View $this
 * @var yii\data\Pagination $pagination
 * @var string $sort
 * @var \Joke[] $models
 * @var array{applied: array<string, mixed>, defaults: array<string, mixed>} $urlFragments
 */

use app\features\rating\RatingShare;
use app\features\widgets\CanonicalLink;
use app\features\widgets\ListSummary;

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

<?= $this->render('@app/features/_partials/faceted-navigation', ['applied' => $urlFragments['applied'], 'defaults' => $urlFragments['defaults']]) ?>

<?= RatingShare::widget(["tableName" => "joke", "tableId" => 0]) ?>
<?= app\features\comment\Widget::widget(["tableName" => "joke", "tableId" => 0]) ?>
