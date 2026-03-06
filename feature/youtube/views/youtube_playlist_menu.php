<?php

use yii\helpers\Url;

?>

<?php /** @var array $playlists */ ?>

<h3 class="sidebarWidget__title">Videos</h3>

<ul class="sidebarWidget__list">
    <li class="sidebarWidget__item"><a class="sidebarWidget__link" href="<?= Url::toRoute(['video/index']) ?>">Videos für Bassisten</a></li>
    <?php foreach ($playlists as $playlist): ?>
        <li class="sidebarWidget__item"><a class="sidebarWidget__link" href="<?= Url::toRoute(['/youtube/index', 'segment' => $playlist['segment']]) ?>"><?= $playlist['title'] ?></a></li>
    <?php endforeach; ?>
</ul>
