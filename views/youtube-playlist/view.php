<?php
/**
 * @var yii\web\View $this
 * @var array $playlist
 * @var array $playlistItem
 * @var ?string $prevId
 * @var ?string $nextId
 */
?>

<?php $this->title = $playlistItem['title'] . ' | ' . $playlist['title'] . ' | Videos' ?>
<h1><?= $playlistItem['title'] ?></h1>

<div class="ratio ratio--16x9">
    <iframe src="https://www.youtube.com/embed/<?= $playlistItem['video_id'] ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>

<?php $this->beginBlock('sidebar') ?>
    <?= $this->render('/_partials/menu_videos.php') ?>
<?php $this->endBlock() ?>

<div class="pager">
    <div class="pager__prev"><?php if ($prevId):
        ?><a up-layer="current" href="/videos/<?= $playlist['segment'] ?>/<?= $prevId ?>">Vorheriges Video</a><?php
                             endif; ?></div>
    <div class="pager__next"><?php if ($nextId):
        ?><a up-layer="current" href="/videos/<?= $playlist['segment'] ?>/<?= $nextId ?>">Nächstes Video</a><?php
                             endif; ?></div>
</div>

<style>
    .pager {
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding-top: 1.25rem;
    }
</style>