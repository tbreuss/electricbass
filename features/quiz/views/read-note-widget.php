<?php

/**
 * @var yii\web\View $this
 * @var string[] $notes
 * @var ?string $lead
 * @var ?string $hint
 * @var int $length
 * @var ?string $nextQuizUid
 * @var ?string $categoryUrl
 */

$this->title = 'Bezeichne die Note – ' . $lead . ' | Quiz';
?>

<?= $this->render('_quiz', [
    'title' => 'Bezeichne die Note',
    'lead' => $lead,
    'hint' => $hint,
    'mode' => 'read-note',
    'numberOfQuestions' => $length,
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
    let notesOnly = [];
    let noteIndex = 0;
    let attempts = [];
    let attempt = 0;
    let showNextButton = <?= $nextQuizUid ? 'true' : 'false' ?>;

    function start() {
        notes = getPerfectlyBalancedNonConsecutive(<?= json_encode($notes) ?>, <?= $length ?>);
        notesOnly = notesWithoutHeight(notes);
        noteIndex = 0;
        attempts = [];
        attempt = 0;
        initButtons();
        showForm();
    }

    function notesWithoutHeight(notes) {
        const notesOnly = [];
        notes.forEach((note) => {
            notesOnly.push(note.toLowerCase().substring(0, 1));
        });
        return notesOnly;
    }

    function initButtons() {
        uiButtons.forEach((button) => {
            const [note, _] = button.value.split(':');
            if (notesOnly.includes(note)) {
                button.disabled = false;
            }
        });
    }

    function getPerfectlyBalancedNonConsecutive(array, count) {
        if (array.length === 0) return [];
        if (array.length === 1) return Array(count).fill(array);

        const result = [];
        let last = null;
        let remaining = [...array]; // Will rebuild after each full cycle

        for (let i = 0; i < count; i++) {
            if (remaining.length === 0) {
                remaining = [...array]; // Reset for next cycle
            }

            // Filter out last item if possible
            let candidates = remaining.filter(item => item !== last);
            if (candidates.length === 0) candidates = remaining; // fallback

            // Pick random
            const pick = candidates[Math.floor(Math.random() * candidates.length)];
            result.push(pick);
            last = pick;

            // Remove from remaining (for this cycle)
            const index = remaining.indexOf(pick);
            if (index > -1) remaining.splice(index, 1);
        }

        return result;
    }

    function showForm() {
        setNote(notes[noteIndex]);
        uiProgress.style.width = (100 / notes.length * noteIndex) + "%";
        uiForm.classList.remove("is-hidden");
        uiResult.classList.add("is-hidden");
    }

    function showResult() {
        const uniqueNotes = notes.filter((note, index, ref) => ref.indexOf(note) === index);
        const minimumNumberOfAttempts = attempts.length;
        const maximumNumberOfAttempts = uniqueNotes.length * attempts.length;
        const diffAttempts = maximumNumberOfAttempts - minimumNumberOfAttempts;
        const effectiveAttempts = attempts.reduce((acc, num) => acc + num, 0) - minimumNumberOfAttempts;
        const accuracy = Math.round((100 - (100 / diffAttempts * effectiveAttempts)) * 10) / 10;

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
        if (!showNextButton) {
            uiNextQuizButton.classList.add('is-hidden');
        }

    }

    uiNotes.addEventListener("click", (event) => {
        if (event.target.tagName !== "BUTTON") {
            return;
        }

        attempt++;

        const [selectedNote, _] = event.target.value.split(":");

        if (selectedNote === notesOnly[noteIndex]) {
            attempts[noteIndex] = attempt;
            noteIndex++;
            uiProgress.style.width = (100 / 10 * noteIndex) + "%";
            showForm();
            initButtons();
            attempt = 0;
        } else {
            attempts[noteIndex] = attempt;
            event.target.disabled = true;
        }

        //uiDebug.innerHTML = JSON.stringify(attempts);

        if (noteIndex >= notes.length) {
            showResult();
        }
    });

    uiPlayAgainButton.addEventListener("click", () => {
        start();
    });

    uiNextQuizButton.addEventListener("click", () => {
        window.open("<?= app\helpers\Url::to('/quiz/' . $nextQuizUid) ?>", "_self");
    });

    uiCloseButton.addEventListener("click", () => {
        window.open("<?= app\helpers\Url::to($categoryUrl) ?>", "_self");
    });

    uiHelpButton && uiHelpButton.addEventListener("click", () => alert("Help clicked"));

    start();
</script>
