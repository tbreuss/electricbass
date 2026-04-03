<?php
/**
 * @var string $imageAlt
 * @var string $imageSrc
 * @var string $scoreName
 * @var string $pdfUrl
 * @var string $pdfSize
 * @var string $midiUrl
 * @var string $midiSize
 */
?>

<div class="shortcode shortcode--score score">
    <img class="img-fluid score__image" src="<?= $imageSrc ?>" alt="<?= $imageAlt ?>">
    <div class="score__downloads">
        <h3 class="score__title">Downloads</h3>
        <div class="score__name">Dateien zum Herunterladen</div>
        <?php if ($pdfSize !== ''): ?>
        <div class="score__pdf">
            <a class="score__link score__link--pdf" href="<?= $pdfUrl ?>"><?= $scoreName ?></a>
            <span class="score__size">PDF, <?= strtoupper($pdfSize) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($midiSize !== ''): ?>
        <div class="score__mid">
            <a class="score__link score__link--midi" href="<?= $midiUrl ?>"><?= $scoreName ?></a>
            <span class="score__size">MIDI, <?= strtoupper($midiSize) ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .score {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    .score__image {
        margin-bottom: 2rem;
    }
    .score__downloads {
        background-color: #f8f8f8;
        padding: 1rem;
    }
    .score__size:before {
        content: "[";
    }
    .score__size:after {
        content: "]";
    }
</style>
