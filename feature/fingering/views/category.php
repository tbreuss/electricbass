<?php /** @var app\feature\fingering\models\Fingering[] $models */ ?>
<ul class="table-of-contents d-none d-md-block">
    <?php foreach ($models as $model): ?>
        <li><a href="<?= $model->url ?>"><?= $model->title ?></a></li>
    <?php endforeach ?>
</ul>
