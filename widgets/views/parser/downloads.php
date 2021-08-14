<?php
/**
 * @var string $title
 * @var array $items
 */
?>

<div class="shortcode shortcode--downloads downloads">
    <h3 class="downloads__title"><?= $title ?></h3>
    <div class="downloads__name">Dateien zum Herunterladen</div>
    <?php foreach ($items as $item): ?>
    <div class="downloads__item">
        <div class="downloads__linkSize">
            <a class="downloads__link downloads__link--<?= strtolower($item['type']) ?>" href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
            <span class="downloads__size"><?=  $item['type'] ?>, <?=  $item['size'] ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
    .downloads {
        margin-top: 2rem;
        margin-bottom: 2rem;
        background-color: #f8f8f8;
        max-width: 766px;
        padding: 1rem;
    }
    .downloads__size:before {
        content: "[";
    }
    .downloads__size:after {
        content: "]";
    }
</style>
