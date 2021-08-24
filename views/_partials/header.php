<?php
/**
 * @var string $title
 * @var string $date
 * @var int $comments
 */
?>
<div class="header">
    <h1 class="header__title"><?= $title ?></h1>
    <p class="header__details">
        <?= Yii::$app->formatter->asDate($date, 'long') ?> von Thomas Breuss
        <?php if ($comments > 1): ?>
            | <a class="header__linkToComments" href="#comments"><?= $comments ?> Kommentare</a>
        <?php elseif ($comments > 0): ?>
            | <a class="header__linkToComments" href="#comments">1 Kommentar</a>
        <?php endif; ?>
    </p>
</div>
