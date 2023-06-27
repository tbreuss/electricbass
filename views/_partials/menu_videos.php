<?php

use yii\helpers\Url;

?>

<h3 class="sidebarWidget__title">Videos</h3>

<ul class="sidebarWidget__list">
    <li class="sidebarWidget__item"><a class="sidebarWidget__link" href="<?= Url::toRoute(['video/index']) ?>">Videos für Bassisten</a></li>
    <li class="sidebarWidget__item"><a class="sidebarWidget__link" href="<?= Url::toRoute(['youtube-playlist/index', 'segment' => 'isolierter-bass']) ?>">Isolierte Bass Tracks</a></li>
    <li class="sidebarWidget__item"><a class="sidebarWidget__link" href="<?= Url::toRoute(['youtube-playlist/index', 'segment' => 'solo-bass']) ?>">Solo Bass Songs</a></li>
</ul>
