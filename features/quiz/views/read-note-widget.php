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
    const uiButtonAgain = document.querySelector(".quiz-result-again-button");
    const uiButtonNext = document.querySelector(".quiz-result-next-button");
    const uiButtonClose = document.querySelector(".quiz-header-close");
    const uiButtonHelp = document.querySelector(".quiz-header-help");

    let notes = [];
    let note = 0;
    let attempts = [];
    let attempt = 0;

    function init() {
        notes = <?= json_encode($quiz) ?>;
        note = 0;
        attempts = [];
        attempt = 0;

        const buttons = uiNotes.querySelectorAll(".quiz-notes-button")
        buttons.forEach((button) => {
            const [note, _] = button.value.split(':');
            if (notes.includes(note)) {
                button.disabled = false;
            }
        });

        uiProgress.style.width = 0;
    }

    function showForm() {
        setNote(notes[note]);
        uiProgress.style.width = (100 / notes.length * note) + "%";
        uiForm.classList.remove("is-hidden");
        uiResult.classList.add("is-hidden");
    }

    function showResult() {
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
            attempt = 0;
        } else {
            attempts[note] = attempt;
        }

        uiDebug.innerHTML = JSON.stringify(attempts);

        if (note >= notes.length) {
            showResult();
            return;
        }
    });

    uiButtonAgain.addEventListener("click", () => {
        init();
        showForm();
    });

    uiButtonNext.addEventListener("click", () => {
        window.open("/quiz?uebung=2", "_self");
    });

    uiButtonClose.addEventListener("click", () => alert("Close clicked"));

    uiButtonHelp.addEventListener("click", () => alert("Help clicked"));

    init();
    showForm();
</script>
