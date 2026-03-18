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

<script type="module">
    import { setNote } from '<?= app\features\quiz\Asset::register($this)->baseUrl ?>/script.js';

    const uiForm = document.querySelector(".quiz-form");
    const uiResult = document.querySelector(".quiz-result");
    const uiProgress = document.querySelector(".quiz-header-progress div");
    const uiDebug = document.querySelector(".quiz-debug");
    const uiNotes = document.querySelector(".quiz-notes");
    const uiButtons = uiNotes.querySelectorAll(".quiz-notes-button")
    const uiPlayAgainButton = document.querySelector(".quiz-result-again-button");
    const uiNextQuizButton = document.querySelector(".quiz-result-next-button");
    const uiCloseButton = document.querySelector(".quiz-header-close");
    const uiHelpButton = document.querySelector(".quiz-header-help");
    const uiResultTitle = document.querySelector(".quiz-result-title");
    const uiResultPoints = document.querySelector(".quiz-result-boxes-points-value");
    const uiResultAccuracy = document.querySelector(".quiz-result-boxes-accuracy-value");

    let notes = [];
    let note = 0;
    let attempts = [];
    let attempt = 0;

    function start() {
        notes = <?= json_encode($quiz) ?>;
        note = 0;
        attempts = [];
        attempt = 0;
        initButtons();
        showForm();
    }

    function initButtons() {
        uiButtons.forEach((button) => {
            const [note, _] = button.value.split(':');
            if (notes.includes(note)) {
                button.disabled = false;
            }
        });
    }

    function showForm() {
        setNote(notes[note]);
        uiProgress.style.width = (100 / notes.length * note) + "%";
        uiForm.classList.remove("is-hidden");
        uiResult.classList.add("is-hidden");
    }

    function showResult() {
        const uniqueNotes = notes.filter((note, index, ref) => ref.indexOf(note) === index);
        const minimumNumberOfAttempts = attempts.length;
        const maximumNumberOfAttempts = uniqueNotes.length * attempts.length;
        const diffAttempts = maximumNumberOfAttempts - minimumNumberOfAttempts;
        const effectiveAttempts = attempts.reduce((acc, num) => acc + num, 0) - minimumNumberOfAttempts;
        const accuracy = 100 - (100 / diffAttempts * effectiveAttempts);

        let title = "Zurück zum Start";
        let points = 10;
        if (accuracy === 100) {
            title = "Perfekt gemacht!";
            points = 50;
        } else if (accuracy >= 75) {
            title = "Gut gemacht!";
            points = 40;
        } else if (accuracy >= 50) {
            title = "Okay gemacht";
            points = 30;
        } else if (accuracy >= 25) {
            title = "Das geht noch besser";
            points = 20;
        }

        uiResultTitle.innerHTML = title;
        uiResultPoints.innerHTML = points;
        uiResultAccuracy.innerHTML = accuracy + "%";

        uiForm.classList.add("is-hidden");
        uiResult.classList.remove("is-hidden");
    }

    uiNotes.addEventListener("click", (event) => {
        if (event.target.tagName !== "BUTTON") {
            return;
        }

        attempt++;

        const [selectedNote, _] = event.target.value.split(":");

        if (selectedNote === notes[note]) {
            attempts[note] = attempt;
            note++;
            uiProgress.style.width = (100 / 10 * note) + "%";
            showForm();
            initButtons();
            attempt = 0;
        } else {
            attempts[note] = attempt;
            event.target.disabled = true;
        }

        //uiDebug.innerHTML = JSON.stringify(attempts);

        if (note >= notes.length) {
            showResult();
        }
    });

    uiPlayAgainButton.addEventListener("click", () => {
        start();
    });

    uiNextQuizButton.addEventListener("click", () => {
        window.open("/quiz?uebung=2", "_self");
    });

    uiCloseButton.addEventListener("click", () => alert("Close clicked"));

    uiHelpButton.addEventListener("click", () => alert("Help clicked"));

    start();
</script>
