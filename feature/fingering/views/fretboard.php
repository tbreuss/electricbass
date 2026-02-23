<?php yii\widgets\Spaceless::begin(); ?>
<?php

/**
 * @var array $config
 * @var array $notes
 * @var array $strings
 * @var array $frets
 * @var ?int $position
 * @var int $expandPosition
 */

// Setup
$numberOfStrings = count($strings);
$numberOfFrets = count($frets);

// Layout
$fretThickness = 5;
$fretSpacing = 60;
$fretTotalWidth = $fretThickness + $fretSpacing;
$stringSpacing = 25;
$stringThickness = 2;
$stringTotalHeight = $stringThickness + $stringSpacing;
$dotRadius = $stringSpacing * 0.3;
$dots = [3, 5, 7, 9, 15, 17, 19, 21];
$doubleDots = [12, 24];
$noteHeight = $stringSpacing * 1;
$noteRadius = $noteHeight / 4;
$noteWidth = $noteHeight * 1.12;
$noteFingerXOffset = $noteWidth + ($stringSpacing / 10);
$noteFingerYOffset = $noteHeight + ($stringSpacing / 10);
$paddingTop = isset($position) ? $noteHeight * 1.6 : $noteHeight;
$paddingRight = $noteWidth;
$paddingBottom = $noteHeight * 1.2;
$paddingLeft = $noteWidth * 1.2;
$fretboardWidth = ($numberOfFrets * $fretThickness) + (($numberOfFrets - 1) * $fretSpacing);
$fretboardHeight = ($numberOfStrings * $stringThickness) + (($numberOfStrings - 1) * $stringSpacing);
$totalWidth = $paddingLeft + $fretboardWidth + $paddingRight;
$totalHeight = $paddingTop + $fretboardHeight + $paddingBottom;

// Fonts
$noteLabelSize = $noteHeight * 0.72;
$fretNumberSize = 13;
$noteFingerSize = $stringSpacing / 2;
$stringFontSize = 20;
$positionFontSize = 20;

// Colors
$dotColor = '#bbbbbb';
$fretboardColor = '#eeeeee';
$fretColor = '#888888';
$noteColor = '#222222';
$noteFingerColor = '#999999';
$noteLabelColor = '#ffffff';
$positionColor = '#cccccc';
$positionFontColor = '#888888';
$viewBoxBackgroundColor = '#f9f9f9';

?>

<svg class="fretboard fretboard--<?= $config['colors'] ?>" viewBox="0 0 <?= $totalWidth ?> <?= $totalHeight ?>" xmlns="http://www.w3.org/2000/svg" width="100%" style="background-color:<?= $viewBoxBackgroundColor ?>">

    <!-- board -->
    <rect class="fretboard__board" x="<?= $paddingLeft ?>" y="<?= $paddingTop ?>" width="<?= $fretboardWidth ?>" height="<?= $fretboardHeight ?>" fill="<?= $fretboardColor ?>" />

    <!-- position -->
    <?php if (!is_null($position)): ?>
        <?php [$positionFretFrom, $positionFretTo] = tebe\tonal\fretboard\positionBound($position, $expandPosition); ?>
        <?php $x = max($paddingLeft + $fretThickness, $paddingLeft + $fretThickness + ($positionFretFrom - 1) * $fretTotalWidth) ?>
        <?php $y = $paddingTop ?>
        <?php $width = ($positionFretTo - $positionFretFrom + ($positionFretFrom === 0 ? 0 : 1)) * $fretTotalWidth - $fretThickness; //($positionWidth - (($position === 1 && $positionStretched) ? 1 : 0)) * $fretTotalWidth - $fretThickness ?>
        <?php $height = $fretboardHeight ?>
        <rect class="fretboard__position" x="<?= $x ?>" y="<?= $y ?>" width="<?= $width ?>" height="<?= $height ?>" fill="<?= $positionColor ?>" />
        <text dominant-baseline="hanging" class="fretboard__positionText" x="<?= $x ?>" y="8" fill="<?= $positionFontColor ?>" font-size="<?= $positionFontSize ?>"><?= (new NumberFormatter('@numbers=roman', NumberFormatter::DECIMAL))->format($position) ?>. Lage</text>
    <?php endif; ?>

    <!-- dots -->
    <?php if (!empty($config['showDots'])): ?>
        <?php foreach ($frets as $fretIndex => $fretNumber): ?>
            <?php $cx = $paddingLeft + ($fretIndex * ($fretTotalWidth) - ($fretSpacing / 2)) ?>
            <?php $cy = $paddingTop + ($fretboardHeight / 2) ?>
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
    <?php foreach (array_keys($strings) as $string): ?>
        <?php $y = $paddingTop + $string * $stringTotalHeight ?>
        <rect class="fretboard__string" x="<?= $paddingLeft ?>" y="<?= $y ?>" width="<?= $fretboardWidth ?>" height="<?= $stringThickness ?>" />
    <?php endforeach; ?>

    <!-- string names -->
    <?php if (!empty($config['showStringNames'])): ?>
        <?php foreach ($strings as $string => $stringLabel): ?>
            <?php $x = 4; ?>
            <?php $y = $paddingTop + ($string * $stringTotalHeight) + ($stringThickness / 2) ?>
            <text class="fretboard__stringName" x="<?= $x ?>" y="<?= $y ?>" dominant-baseline="middle" font-size="<?= $stringFontSize ?>"><?= $stringLabel ?></text>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- notes -->
    <?php foreach ($notes as ['string' => $stringNumber, 'fret' => $fretNumber, 'label' => $noteLabel, 'hint' => $noteHint, 'chroma' => $chroma]): ?>
        <?php if (($fretIndex = array_search($fretNumber, $frets)) !== false): ?>
            <?php $xNote = empty($fretIndex) ? 0 : $paddingLeft + ((int)$fretIndex * (int)$fretTotalWidth) - ($fretTotalWidth / 2) + ($fretThickness / 2) - ($noteWidth / 2) ?>
            <?php $yNote = $paddingTop + (($stringNumber - 1) * $stringTotalHeight) + ($stringThickness / 2) - ($stringSpacing / 2) ?>
            <?php $xNoteFinger = $xNote  + $noteFingerXOffset ?>
            <?php $yNoteFinger = $yNote + $noteFingerYOffset ?>
            <g class="fretboard__note">
                <rect class="fretboard__noteSymbol fretboard__noteSymbol--<?= $chroma ?>" x="<?= $xNote ?>" y="<?= $yNote ?>" width="<?= $noteWidth ?>" height="<?= $noteHeight ?>" rx="<?= $noteRadius ?>" fill="<?= $noteColor ?>" />
                <text class="fretboard__noteText fretboard__noteText--<?= $chroma ?>" x="<?= $xNote + ($noteWidth / 2) ?>" y="<?= $yNote + ($noteHeight / 2) ?>" font-size="<?= $noteLabelSize ?>" dominant-baseline="central" text-anchor="middle" fill="<?= $noteLabelColor ?>">
                    <?php /*<tspan class="fretboard__noteTextSign" font-size="<?= $noteLabelSize * 0.9 ?>"><?= $noteSign ?></tspan>*/ ?>
                    <tspan class="fretboard__noteTextLabel"><?= $noteLabel ?></tspan>
                </text>
                <text class="fretboard__noteFinger" x="<?= $xNoteFinger ?>" y="<?= $yNoteFinger ?>" fill="<?= $noteFingerColor ?>" font-size="<?= $noteFingerSize ?>"><?= $noteHint ?></text>
            </g>
        <?php endif; ?>
    <?php endforeach; ?>

</svg>
<?php yii\widgets\Spaceless::end(); ?>