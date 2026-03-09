<?php

/** @var yii\web\View $this */ ?>

<?= $this->render('_quiz', [
    'title' => 'Bezeichne die Note',
    'mode' => 'read-note',
]) ?>

<script type="module">
    import { setNote, showCoordinate, toSvgX, toSvgY } from '<?= app\features\quiz\Asset::register($this)->baseUrl ?>/script.js';
    const notes = ['B1', 'C', 'D', 'E', 'F', 'G', 'A', 'B', 'c', 'd', 'e', 'f', 'g', 'a', 'b', 'c1', 'd1', 'e1', 'f1', 'g1'];

    for (let i=0; i <= notes.length; i++){
        setTimeout(() => {
            setNote(notes[i]);
            console.log(notes[i]);
        }, i * 250);
    }

    document.querySelector(".quiz-score").addEventListener("pointermove", (event) => {
        //showCoordinate(toSvgX(event.offsetX), toSvgY(event.offsetY));
    });
</script>
