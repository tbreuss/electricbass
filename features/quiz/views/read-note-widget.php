<?php /** @var yii\web\View $this */ ?>
<?php $alphaTabAsset = app\features\alphaTab\Asset::register($this) ?>
<?php $quizAsset = app\features\quiz\Asset::register($this) ?>

<div class="quiz quiz-read-note">
    <div class="progress">
        <img src="<?= $quizAsset->baseUrl ?>/x-circle.svg" width="24" height="24" alt="Schliessen">
        <div class="progress-bar">
            <div style="background-color: rgb(114, 172, 81); width: 16.6667%;">&nbsp;</div>
        </div>
        <img src="<?= $quizAsset->baseUrl ?>/help-circle.svg" width="24" height="24" alt="Hilfe">
    </div>
    <h1 class="quiz-title">Bezeichne die Note</h1>
    <svg viewBox="0 0 800 220" xmlns="http://www.w3.org/2000/svg" class="score">
        <g class="upper-help-lines" stroke="#555">
            <line class="upper-ledger-line-3" x1="193" y1="14" x2="240" y2="14" stroke-width="2" visibility="hidden"></line>
            <line class="upper-ledger-line-2" x1="193" y1="34" x2="240" y2="34" stroke-width="2" visibility="hidden"></line>
            <line class="upper-ledger-line-1" x1="193" y1="54" x2="240" y2="54" stroke-width="2" visibility="hidden"></line>
        </g>
        <g class="lines" stroke="#555">
            <line x1="0" y1="74" x2="800" y2="74" stroke-width="2"></line>
            <line x1="0" y1="94" x2="800" y2="94" stroke-width="2"></line>
            <line x1="0" y1="114" x2="800" y2="114" stroke-width="2"></line>
            <line x1="0" y1="134" x2="800" y2="134" stroke-width="2"></line>
            <line x1="0" y1="154" x2="800" y2="154" stroke-width="2"></line>
        </g>
        <g class="lower-help-lines" stroke="#555">
            <line class="lower-ledger-line-1" x1="193" y1="174" x2="240" y2="174" stroke-width="2" visibility="hidden"></line>
            <line class="lower-ledger-line-2" x1="193" y1="194" x2="240" y2="194" stroke-width="2" visibility="hidden"></line>
        </g>
        <text x="20" y="94" class="key"></text>
        <text x="200" y="94" class="draggable-note" visibility="hidden">𝅝</text>
        <text x="200" y="94" class="selected-note" visibility="hidden">𝅝</text>
        <line class="x-coordinate" x1="0" y1="-1" x2="800" y2="-1" stroke="#eee" stroke-width="1"></line>
        <line class="y-coordinate" x1="-1" y1="0" x2="-1" y2="220" stroke="#eee" stroke-width="1"></line>
    </svg>
    <div class="notes">
        <button value="c:">c</button>
        <button value="d:">d</button>
        <button value="e:">e</button>
        <button value="f:" disabled="">f</button>
        <button value="g:" disabled="">g</button>
        <button value="a:" disabled="">a</button>
        <button value="h:" disabled="">h</button>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const svg = document.querySelector(".score");
        const draggableNote = document.querySelector(".draggable-note");
        const selectedNote = document.querySelector(".selected-note");
        const upperLedgerLine1 = document.querySelector(".upper-ledger-line-1");
        const upperLedgerLine2 = document.querySelector(".upper-ledger-line-2");
        const upperLedgerLine3 = document.querySelector(".upper-ledger-line-3");
        const lowerLedgerLine1 = document.querySelector(".lower-ledger-line-1");
        const lowerLedgerLine2 = document.querySelector(".lower-ledger-line-2");
        const xCoordinate = document.querySelector(".x-coordinate");
        const yCoordinate = document.querySelector(".y-coordinate");

        function calcNoteY(offsetY) {
            let y = Math.floor(offsetY / 10) * 10 + 4;
            y = Math.min(204, y);
            y = Math.max(14, y);
            return y;
        }

        function selectNote(offsetY) {
            const y = calcNoteY(offsetY);
            selectedNote.setAttribute("y", y);
            selectedNote.setAttribute("visibility", "visible");
        }

        function showNote(offsetY) {
            const y = calcNoteY(offsetY);
            const ySelected = selectedNote.getAttribute("y");
            draggableNote.setAttribute("visibility", "visible");
            draggableNote.setAttribute("y", y);
            upperLedgerLine1.setAttribute("visibility", y <= 54 || ySelected <= 54 ? "visible" : "hidden");
            upperLedgerLine2.setAttribute("visibility", y <= 34 || ySelected <= 34 ? "visible" : "hidden");
            upperLedgerLine3.setAttribute("visibility", y <= 14 || ySelected <= 14 ? "visible" : "hidden");
            lowerLedgerLine1.setAttribute("visibility", y >= 174 || ySelected >= 174 ? "visible" : "hidden");
            lowerLedgerLine2.setAttribute("visibility", y >= 194 || ySelected >= 194 ? "visible" : "hidden");
        }

        function hideNote() {
            draggableNote.setAttribute("visibility", "hidden");
            const ySelected = selectedNote.getAttribute("y");
            upperLedgerLine1.setAttribute("visibility", ySelected <= 54 ? "visible" : "hidden");
            upperLedgerLine2.setAttribute("visibility", ySelected <= 34 ? "visible" : "hidden");
            upperLedgerLine3.setAttribute("visibility", ySelected <= 14 ? "visible" : "hidden");
            lowerLedgerLine1.setAttribute("visibility", ySelected >= 174 ? "visible" : "hidden");
            lowerLedgerLine2.setAttribute("visibility", ySelected >= 194 ? "visible" : "hidden");
        }

        function toSvgX(x) {
            return 800 / svg.clientWidth * x;
        }

        function toSvgY(y) {
            return 220 / svg.clientHeight * y;
        }

        function showCoordinate(x, y) {
            xCoordinate.setAttribute("y1", y);
            xCoordinate.setAttribute("y2", y);
            yCoordinate.setAttribute("x1", x);
            yCoordinate.setAttribute("x2", x);
        }

        document.querySelector(".score").addEventListener("pointerdown", function(event) {
            selectNote(toSvgY(event.offsetY));
            showNote(toSvgY(event.offsetY));
        });

        document.querySelector(".score").addEventListener("pointermove", function(event) {
            showNote(toSvgY(event.offsetY));
            // showCoordinate(toSvgX(event.offsetX), toSvgY(event.offsetY));
        });

        document.querySelector(".score").addEventListener('pointerenter', function(event) {
            showNote(toSvgY(event.offsetY));
        });

        document.querySelector(".score").addEventListener('pointerleave', function(event) {
            hideNote();
        });
    });
</script>
<style>
    @font-face {
        font-family: "Bravura";
        src: url("<?= $alphaTabAsset->baseUrl ?>/font/Bravura.woff2") format("woff2");
    }
    .quiz-title {
        text-align: center;
    }
    .progress {
        display: flex;
        color: #ebebeb;
    }
    .progress img {
        filter: brightness(0) saturate(100%) invert(66%) sepia(94%) saturate(1%) hue-rotate(6deg) brightness(106%) contrast(89%);
    }
    .progress-bar {
        width: 100%;
        height: 24px;
        background-color: #ebebeb;
        border-radius: 20px;
        line-height: 1;
        font-size: 24px;
    }
    .progress-bar div {
        box-sizing: inherit;
        background-color: #ebebeb;
        border-radius: 20px;
        width: 0%;
        transition: width .5s;
    }
    .notes {
        text-align: center;
    }
    .notes button {
        cursor: pointer;
        margin: 5px;
        padding: 8px 15px 11px;
        border-radius: 10px;
        border: 2px solid #d2d4d8;
        background-image: none;
        min-width: 4%;
        max-width: 100%;
        text-align: center;
        color: #000;
    }
    .notes button[disabled] {
        color: #e0e0e0;
        border-color: #e0e0e0;
        box-shadow: none;
        cursor: default;
        background-color: #fff;
    }
    .score {
        /*outline: 1px solid green;*/
        cursor: pointer;
        width: 100%;
    }
    .score .draggable-note {
        fill: #999;
    }
    .score text {
        font-family: "Bravura", sans-serif;
    }
    .score .key {
        font-size: 84px;
    }
    .score .draggable-note, .score .selected-note {
        font-size: 70px;
    }
</style>
