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

$this->params['breadcrumbs'][] = 'Bassistenwitze';
$this->params['pageTitle'] = 'Witze, Flachwitze & Dad-Jokes: Die beste Sammlung für Bassisten ';
$this->params['metaDescription'] = 'Sammlung der besten Witze, Flachwitze und Dad-Jokes aller Zeiten. Mit einem speziellen Fokus auf Humor für Bassisten und Witze rund ums Thema Bass. 😂';
CanonicalLink::widget(['isPaginated' => false]);
?>
<div class="content">
    <h1>Witze, Flachwitze & Dad-Jokes für Bassisten</h1>
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
