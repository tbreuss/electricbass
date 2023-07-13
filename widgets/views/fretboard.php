<?php

/**
 * @var array $config
 * @var array $notes
 */

// Setup
$strings = [
    ['note' => 'E', 'stringNumber' => 4],
    ['note' => 'A', 'stringNumber' => 3],
    ['note' => 'D', 'stringNumber' => 2],
    ['note' => 'G', 'stringNumber' => 1],
];
$numberOfStrings = count($strings);
$frets = range(0, 24);
$numberOfFrets = count($frets);

// Layout
$paddingTop = 25;
$paddingRight = 20;
$paddingBottom = 25;
$paddingLeft = 30;
$fretThickness = 5;
$fretSpacing = 60;
$fretTotalWidth = $fretThickness + $fretSpacing;
$stringSpacing = 25;
$stringThickness = 2;
$stringTotalHeight = $stringThickness + $stringSpacing;
$fretboardWidth = ($numberOfFrets * $fretThickness) + (($numberOfFrets - 1) * $fretSpacing);
$fretboardHeight = ($numberOfStrings * $stringThickness) + (($numberOfStrings -1) * $stringSpacing);
$totalWidth = $paddingLeft + $fretboardWidth + $paddingRight;
$totalHeight = $paddingTop + $fretboardHeight + $paddingBottom;
$dotRadius = 5;
$dots = [3, 5, 7, 9, 15, 17, 19, 21];
$doubleDots = [12, 24];
$noteHeight = 24;
$noteRadius = $noteHeight / 4;
$noteWidth = 28;

// Fonts
$noteLabelSize = 18;
$fretNumberSize = 13;
$noteFingerSize = 13;

// Colors
$dotColor = '#bbbbbb';
$fretboardColor = '#eeeeee';
$fretColor = '#888888';
$noteColor = '#222222';
$noteFingerColor = '#999999';
$noteLabelColor = '#ffffff';
$viewBoxBackgroundColor = '#f9f9f9';

?>

<svg class="fretboard" viewBox="0 0 <?= $totalWidth ?> <?= $totalHeight ?>" xmlns="http://www.w3.org/2000/svg" width="100%" style="background-color:<?= $viewBoxBackgroundColor ?>">

    <!-- board -->
    <rect class="fretboard__board" x="<?= $paddingLeft ?>" y="<?= $paddingTop ?>" width="<?= $fretboardWidth ?>" height="<?= $fretboardHeight ?>" fill="<?= $fretboardColor ?>" />

    <!-- dots -->
    <?php if (!empty($config['showDots'])): ?>
        <?php foreach ($frets as $fretIndex => $fretNumber): ?>
            <?php $cx = $paddingLeft + ($fretIndex * ($fretTotalWidth) - ($fretSpacing / 2)) ?>
            <?php $cy = $paddingTop + ($totalHeight / 2) - $paddingBottom ?>
            <?php if (in_array($fretNumber, $dots)): ?>
                <circle class="fretboard__dot" cx="<?= $cx ?>" cy="<?= $cy ?>" r="<?= $dotRadius ?>" fill="<?= $dotColor ?>" />
            <?php endif; ?>
            <?php if (in_array($fretNumber, $doubleDots)): ?>
                <?php $cyOne = $cy - $stringTotalHeight; ?>
                <?php $cyTwo = $cy + $stringTotalHeight; ?>
                <circle class="fretboard__dot fretboard__dot--double" cx="<?= $cx ?>" cy="<?= $cyOne ?>" r="<?= $dotRadius ?>" fill="<?= $dotColor ?>" />
                <circle class="fretboard__dot fretboard__dot--double" cx="<?= $cx ?>" cy="<?= $cyTwo ?>" r="<?= $dotRadius ?>" fill="<?= $dotColor ?>" />
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- frets -->
    <?php foreach (array_keys($frets) as $fretIndex): ?>
        <?php $x = $paddingLeft + ($fretIndex * $fretTotalWidth); ?>
        <rect class="fretboard__fret" x="<?= $x ?>" y="<?= $paddingTop ?>" width="<?= $fretThickness ?>" height="<?= $fretboardHeight ?>" fill="<?= $fretColor ?>" />
    <?php endforeach; ?>

    <!-- fret numbers -->
    <?php if (!empty($config['showFretNumbers'])): ?>
        <?php foreach ($frets as $fretIndex => $fretNumber): ?>
            <?php $x = $paddingLeft + ($fretIndex * $fretTotalWidth) + ($fretThickness / 2); ?>
            <?php $y = $totalHeight - $paddingBottom + ($noteHeight / 2) + ($fretNumberSize * 0.75) ?>
            <text class="fretboard__fretNumber" x="<?= $x ?>" y="<?= $y ?>" font-size="<?= $fretNumberSize ?>" dominant-baseline="auto" text-anchor="middle"><?= $fretNumber ?></text>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- strings -->
    <?php foreach ($strings as $string): ?>
        <?php $y = $paddingTop + ($string['stringNumber'] - 1) * $stringTotalHeight ?>
        <rect class="fretboard__string" x="<?= $paddingLeft ?>" y="<?= $y ?>" width="<?= $fretboardWidth ?>" height="<?= $stringThickness ?>" />
    <?php endforeach; ?>

    <!-- string names -->
    <?php if (!empty($config['showStringNames'])): ?>
        <?php foreach ($strings as $string): ?>
            <?php $x = $paddingLeft / 4 * 0.125; ?>
            <?php $y = $paddingTop + (($string['stringNumber'] - 1) * $stringTotalHeight) + ($stringThickness / 2) ?>
            <text class="fretboard__stringName" x="<?= $x ?>" y="<?= $y ?>" dominant-baseline="middle"><?= $string['note'] ?></text>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- notes -->
    <?php foreach ($notes as $note): ?>
        <?php [$stringNumber, $fretNumber, $noteFunction, $noteSign, $noteLabel, $fingering] = app\widgets\Fretboard::stringNumberFromNote($note, $strings) ?>
        <?php if (($fretIndex = array_search($fretNumber, $frets)) !== false): ?>
            <?php $xNote = $paddingLeft + ($fretIndex * $fretTotalWidth) + ($fretThickness / 2) - ($noteWidth / 2) ?>
            <?php $yNote = $paddingTop + (($stringNumber - 1) * $stringTotalHeight) + ($stringThickness / 2) - ($stringSpacing / 2) ?>
            <?php $xNoteFinger = $xNote  + $noteWidth + ($noteFingerSize / 5) ?>
            <?php $yNoteFinger = $yNote + $noteHeight + ($noteFingerSize / 5) ?>
            <g class="fretboard__note">
                <rect class="fretboard__noteSymbol <?php if ($config['colors'] === 'intervals'): ?>fretboard__noteSymbol--<?= $noteFunction ?><?php endif; ?>" x="<?= $xNote ?>" y="<?= $yNote ?>" width="<?= $noteWidth ?>" height="<?= $noteHeight ?>" rx="<?= $noteRadius ?>" fill="<?= $noteColor ?>" />
                <text class="fretboard__noteText <?php if ($config['colors'] === 'intervals'): ?>fretboard__noteText--<?= $noteFunction ?><?php endif; ?>" x="<?= $xNote + ($noteWidth / 2) ?>" y="<?= $yNote + ($noteHeight / 2) ?>" font-size="<?= $noteLabelSize ?>" dominant-baseline="central" text-anchor="middle" fill="<?= $noteLabelColor ?>">
                    <tspan class="fretboard__noteTextSign" font-size="<?= $noteLabelSize * 0.9 ?>"><?= $noteSign ?></tspan>
                    <tspan class="fretboard__noteTextLabel"><?= $noteLabel ?></tspan>
                </text>
                <text class="fretboard__noteFinger" x="<?= $xNoteFinger ?>" y="<?= $yNoteFinger ?>" fill="<?= $noteFingerColor ?>" font-size="<?= $noteFingerSize ?>"><?= $fingering ?></text>
            </g>
        <?php endif; ?>
    <?php endforeach; ?>

</svg>
