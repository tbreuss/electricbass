<?php

/**
 * @var yii\web\View $this
 * @var array $playlist
 * @var array $playlistItems
 */

use yii\helpers\Url;

?>

<?php $this->title = $playlist['title'] . ' | Videos'; ?>
<?php $this->params['metaDescription'] = $playlist['description']; ?>
<?php $this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => Url::previous('video')]; ?>
<?php $this->params['breadcrumbs'][] = $playlist['title']; ?>

<h1><?= $playlist['title'] ?></h1>

<div class="widgetListSummary">
    <div class="row">
        <div class="col-6 widgetListSummary__summary"><?= count($playlistItems) ?><span> Ergebnisse</span></div>
    </div>
</div>

<div class="row videoList">
    <?php foreach ($playlistItems as $item): ?>
        <div class="col-6 col-xl-4 videoList__cell">
            <a up-layer="new" up-size="large" href="/videos/<?= $playlist['segment'] ?>/<?= $item['id'] ?>" class="videoList__link">
                <img loading="lazy" class="img-fluid videoList__cover" width="<?= $item['thumbnail']['width'] ?>" height="<?= $item['thumbnail']['height'] ?>" src="<?= $item['thumbnail']['url'] ?>" alt="<?= $item['title'] ?>">
                <div class="videoList__title"><?= $item['title'] ?></div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="row videoListDesc">
    <?= yii\helpers\Markdown::process($playlist['description']) ?>
</div>

<?php $this->beginBlock('sidebar') ?>
    <?= app\widgets\YoutubePlaylistMenu::widget() ?>
<?php $this->endBlock() ?>
