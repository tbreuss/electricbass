<?php /** @var yii\web\View $this */ ?>
<?php /** @var string $mode */ ?>
<?php /** @var string $title */ ?>
<?php /** @var ?string $lead */ ?>
<?php /** @var ?string $hint */ ?>
<?php /** @var int $numberOfQuestions */ ?>

<?php $alphaTabAsset = app\features\alphaTab\Asset::register($this) ?>
<?php $quizAsset = app\features\quiz\Asset::register($this) ?>

<style>@font-face { font-family: "Bravura"; src: url("<?= $alphaTabAsset->baseUrl ?>/font/Bravura.woff2") format("woff2"); }</style>

<div class="quiz quiz--<?= $mode ?>">
    <div class="quiz-form is-hidden">
        <div class="quiz-header">
            <img class="quiz-header-close" src="<?= $quizAsset->baseUrl ?>/x-circle.svg" width="24" height="24" alt="Schliessen">
            <div class="quiz-header-progress">
                <div style="background-color: rgb(114, 172, 81);">&nbsp;</div>
            </div>
            <?php if ($hint):
                ?><img class="quiz-header-help" src="<?= $quizAsset->baseUrl ?>/help-circle.svg" width="24" height="24" alt="Hilfe"><?php
            endif ?>
        </div>
        <h1 class="quiz-title"><?= $title ?></h1>
        <p class="quiz-lead"><?= $lead ? $lead . ' – ' : '' ?><?= $numberOfQuestions ?> Fragen</p>
        <svg viewBox="0 0 800 220" xmlns="http://www.w3.org/2000/svg" class="quiz-score">
            <g class="quiz-score-upper-ledger-lines">
                <line class="quiz-score-upper-ledger-lines-3" x1="193" y1="14" x2="240" y2="14" stroke-width="2" visibility="hidden"></line>
                <line class="quiz-score-upper-ledger-lines-2" x1="193" y1="34" x2="240" y2="34" stroke-width="2" visibility="hidden"></line>
                <line class="quiz-score-upper-ledger-lines-1" x1="193" y1="54" x2="240" y2="54" stroke-width="2" visibility="hidden"></line>
            </g>
            <g class="quiz-score-lines">
                <line x1="0" y1="74" x2="800" y2="74" stroke-width="2"></line>
                <line x1="0" y1="94" x2="800" y2="94" stroke-width="2"></line>
                <line x1="0" y1="114" x2="800" y2="114" stroke-width="2"></line>
                <line x1="0" y1="134" x2="800" y2="134" stroke-width="2"></line>
                <line x1="0" y1="154" x2="800" y2="154" stroke-width="2"></line>
            </g>
            <g class="quiz-score-lower-ledger-lines">
                <line class="quiz-score-lower-ledger-lines-1" x1="193" y1="174" x2="240" y2="174" stroke-width="2" visibility="hidden"></line>
                <line class="quiz-score-lower-ledger-lines-2" x1="193" y1="194" x2="240" y2="194" stroke-width="2" visibility="hidden"></line>
            </g>
            <text x="20" y="94" class="quiz-score-key"></text>
            <text x="200" y="94" class="quiz-score-draggable-note" visibility="hidden">𝅝</text>
            <text x="200" y="94" class="quiz-score-selected-note" visibility="hidden">𝅝</text>
            <line class="quiz-score-x-coordinate" x1="0" y1="-1" x2="800" y2="-1" stroke-width="1"></line>
            <line class="quiz-score-y-coordinate" x1="-1" y1="0" x2="-1" y2="220" stroke-width="1"></line>
        </svg>
        <?php if ($mode === 'read-note'): ?>
        <div class="quiz-notes">
            <button class="quiz-notes-button" value="c:" disabled>c</button>
            <button class="quiz-notes-button" value="d:" disabled>d</button>
            <button class="quiz-notes-button" value="e:" disabled>e</button>
            <button class="quiz-notes-button" value="f:" disabled>f</button>
            <button class="quiz-notes-button" value="g:" disabled>g</button>
            <button class="quiz-notes-button" value="a:" disabled>a</button>
            <button class="quiz-notes-button" value="b:" disabled>b</button>
        </div>
        <?php endif ?>
    </div>
    <div class="quiz-result is-hidden">
        <h1 class="quiz-result-title"></h1>
        <p class="quiz-lead"><?= $lead ?></p>
        <div class="quiz-result-boxes">
            <div class="quiz-result-boxes-points">
                <h3>Punkte</h3>
                <div class="quiz-result-boxes-points-value"></div>
            </div>
            <div class="quiz-result-boxes-accuracy">
                <h3>Genauigkeit</h3>
                <div class="quiz-result-boxes-accuracy-value"></div>
            </div>
        </div>
        <button class="quiz-result-again-button">Noch einmal</button>
        <button class="quiz-result-next-button">Nächste Übung</button>
    </div>
    <div class="quiz-debug"></div>
</div>
