<?php

/** @var yii\web\View $this */ ?>

<?= $this->render('_quiz', [
    'title' => 'Schreibe den Ton c',
    'mode' => 'write-note',
]) ?>

<script type="module">
    import { hideNote, selectNote, showNote, toSvgY } from '<?= app\features\quiz\Asset::register($this)->baseUrl ?>/script.js';

    document.querySelector(".quiz-score").addEventListener("pointerdown", function(event) {
        selectNote(toSvgY(event.offsetY));
        showNote(toSvgY(event.offsetY));
    });

    document.querySelector(".quiz-score").addEventListener("pointermove", function(event) {
        showNote(toSvgY(event.offsetY));
        // showCoordinate(toSvgX(event.offsetX), toSvgY(event.offsetY));
    });

    document.querySelector(".quiz-score").addEventListener('pointerenter', function(event) {
        showNote(toSvgY(event.offsetY));
    });

    document.querySelector(".quiz-score").addEventListener('pointerleave', function(event) {
        hideNote();
    });
</script>
