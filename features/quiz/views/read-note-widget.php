<?php
/**
 * @var yii\web\View $this
 * @var string[] $quiz
 */
?>

<?= $this->render('_quiz', [
    'title' => 'Bezeichne die Note',
    'mode' => 'read-note',
]) ?>

<div class="quiz-output"></div>

<script type="module">
    import { setNote } from '<?= app\features\quiz\Asset::register($this)->baseUrl ?>/script.js';

    const progress = document.querySelector(".quiz-header-progress div");
    const output = document.querySelector(".quiz-output");

    const notes = <?= json_encode($quiz) ?>;
    let note = 0;
    let attempts = [];
    let attempt = 0;

    setNote(notes[note]);

    document.querySelector(".quiz-notes").addEventListener("click", (event) => {
        if (event.target.tagName !== "BUTTON") {
            return;
        }

        if (note >= notes.length) {
            // finished
            return;
        }

        attempt++;

        const [selectedNote, _] = event.target.value.split(':');

        if (selectedNote === notes[note]) {
            attempts[note] = attempt;
            note++;
            progress.style.width = (100 / 10 * note) + '%';
            setNote(notes[note]);
            attempt = 0;
        } else {
            attempts[note] = attempt;
        }

        if (note >= notes.length) {
            // finished
        }

        output.innerHTML = JSON.stringify(attempts);
    });

    /*
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
    */
</script>
