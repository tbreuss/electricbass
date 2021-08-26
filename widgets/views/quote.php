<?php

if (empty($model)) {
    return;
} ?>

<div class="sidebarWidget sidebarWidget--quote">
    <figure>
        <blockquote class="blockquote">
            <p><?= $model->getText() ?></p>
        </blockquote>
        <figcaption class="blockquote-footer">
            <?= $model->getAuthor() ?>
        </figcaption>
    </figure>
</div>
