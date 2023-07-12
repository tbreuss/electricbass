<?php

/**
 * @var array $config
 * @var array $notes
 */

if (!function_exists('totalWidth')) {

    function totalWidth(int $numberOfFrets, int $fretThickness, int $fretSpacing, int $paddingLeft, int $paddingRight): int
    {
        return $paddingLeft + ($numberOfFrets * $fretThickness) + (($numberOfFrets - 1) * $fretSpacing) + $paddingRight;
    }

    function totalHeight(int $numberOfStrings, int $stringSpacing, int $stringThickness, int $paddingTop, int $paddingBottom): int
    {
        return $paddingTop + ($numberOfStrings * $stringThickness) + (($numberOfStrings -1) * $stringSpacing) + $paddingBottom;
    }

    function stringLength(int $totalWidth, int $paddingLeft, int $paddingRight): int
    {   
        return $totalWidth - $paddingLeft - $paddingRight;
    }

    function stringNameXPosition(int $paddingLeft): float
    {
        return $paddingLeft / 4 * 0.125;
    }

    function stringNameYPosition(int $stringNumber, int $stringSpacing, int $stringThickness, int $paddingTop, int $paddingBottom): int
    {
        return totalHeight($stringNumber, $stringSpacing, $stringThickness, $paddingTop, $paddingBottom) - $paddingBottom - $stringThickness + 8;
    }

    function stringXPosition(int $paddingLeft): int
    {
        return $paddingLeft;
    }

    function stringYPosition(int $stringNumber, int $stringSpacing, int $stringThickness, int $paddingTop, int $paddingBottom): int
    {
        return totalHeight($stringNumber, $stringSpacing, $stringThickness, $paddingTop, $paddingBottom) - $paddingBottom - $stringThickness;
    }

    function dotCxPosition(int $dotNumber, int $fretThickness, int $fretSpacing, int $paddingLeft): int
    {
        return $paddingLeft + ($dotNumber * ($fretSpacing + $fretThickness) - ($fretSpacing / 2));
    }

    function dotCyPosition(int $totalHeight, int $paddingTop, int $paddingBottom): int
    {
        return $paddingTop + ($totalHeight / 2) - $paddingBottom;
    }

    function doubleDotCxPosition(int $dotNumber, int $fretThickness, int $fretSpacing, int $paddingLeft): int
    {
        return dotCxPosition($dotNumber, $fretThickness, $fretSpacing, $paddingLeft);
    }

    function doubleDotCyPosition(int $totalHeight, int $paddingTop, int $paddingBottom, int $stringThickness, int $stringSpacing): array
    {
        $dotCentered = $paddingTop + ($totalHeight / 2) - $paddingBottom;
        $dotOne = $dotCentered - $stringThickness - $stringSpacing;
        $dotTwo = $dotCentered + $stringThickness + $stringSpacing;
        return [$dotOne, $dotTwo];
    }

    function fretHeight(int $totalHeight, int $paddingTop, int $paddingBottom): int
    {
        return $totalHeight - $paddingTop - $paddingBottom;
    }

    function fretNumberXPosition(int $fretNumber, int $fretThickness, int $fretSpacing, int $paddingLeft): int
    {
        $xPosition = $fretNumber * ($fretSpacing + $fretThickness) + $paddingLeft - 1;
        if ($fretNumber > 9) {
            $xPosition -= 4;
        }
        return $xPosition;
    }

    function fretNumberYPosition(int $totalHeight, int $paddingBottom, int $noteHeight, int $fretNumberSize): int
    {
        return $totalHeight - $paddingBottom + ($noteHeight / 2) + ($fretNumberSize * 0.75);
    }

    function fretXPosition(int $fretNumber, int $fretThickness, int $fretSpacing, int $paddingLeft): int
    {
        return $fretNumber * ($fretSpacing + $fretThickness) + $paddingLeft;
    }

    function fretYPosition(int $paddingTop): int
    {
        return $paddingTop;
    }

    function stringNumberFromNote(string $note, array $strings): array
    {
        $noteParts = explode('-', $note);
        $noteName = (string)preg_replace('/[0-9]/', '', $noteParts[0]);
        $fretNumber = (int)preg_replace('/[^0-9]/', '', $noteParts[0]);
        $noteFunction = (string)($noteParts[1] ?? '');
        $fingering = (string)($noteParts[2] ?? '');
        $noteLabel = $noteFunction;
        $noteSign = '';

        $flatOrShartSign = substr($noteLabel, 0, 1);

        if (in_array($flatOrShartSign, ['d', 'b'])) {
            $noteSign = '&#9837;';
            $noteLabel = substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'a') {
            $noteSign = '&#9839;';
            $noteLabel = substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'm') {
            $noteLabel = $flatOrShartSign;
        }

        foreach ($strings as $string) {
            if ($string['note'] === $noteName) {
                return [$string['stringNumber'], $fretNumber, $noteFunction, $noteSign, $noteLabel, $fingering];
            }
        }

        throw new \RuntimeException('Could not determine string number from note');
    }
    
    function noteFingerXPosition(int $fretNumber, int $fretThickness, int $fretSpacing, int $paddingLeft, int $noteWidth, int $noteFingerSize): int
    {
        return noteXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft) + $noteWidth + ($noteFingerSize / 5);
    }

    function noteFingerYPosition(int $stringNumber, int $stringThickness, int $stringSpacing, int $paddingTop, int $noteHeight, int $noteFingerSize): int
    {
        return noteYPosition($stringNumber, $stringThickness, $stringSpacing, $paddingTop) + $noteHeight + ($noteFingerSize / 5);
    }

    function noteLabelXPosition(int $noteXPosition, string $noteFunction): int
    {
        $offset = 9;

        $firstChar = substr($noteFunction, 0, 1);

        if ($firstChar === 'R') {
            $offset -= 1;
        }

        if ($firstChar === 'm') {
            $offset -= 3;
        }

        if (in_array($firstChar, ['a', 'd', 'b'])) {
            $offset = 0;
        }

        return $noteXPosition + $offset;
    }

    function noteLabelYPosition(int $noteYPosition, string $noteFunction): int
    {
        return $noteYPosition + 18;
    }

    function noteXPosition(int $fretNumber, int $fretThickness, int $fretSpacing, int $paddingLeft): int
    {
        return $fretNumber * ($fretThickness + $fretSpacing) + ($fretThickness / 2) + $paddingLeft - 13;
    }

    function noteYPosition(int $stringNumber, int $stringThickness, int $stringSpacing, int $paddingTop): int
    {
        return ($stringNumber - 1) * ($stringThickness + $stringSpacing) + ($stringThickness / 2) + $paddingTop - ($stringSpacing / 2);
    }

}

$dotFill = '#bbbbbb';
$dotRadius = 5;
$dots = [3, 5, 7, 9, 15, 17, 19, 21];
$doubleDots = [12, 24];
$fretBoardFill = '#eeeeee';
$fretFill = '#888888';
$fretNumberSize = 13;
$fretSpacing = 60;
$fretThickness = 5;
$noteFill = '#222222';
$noteFingerColor = '#999999';
$noteFingerSize = 13;
$noteLabelOffsets = [];
$noteLabelSize = 18;
$noteHeight = 24;
$noteRadius = $noteHeight / 4;
$noteWidth = 28;
$numberOfFrets = 9;
$paddingBottom = 25;
$paddingLeft = 30;
$paddingRight = 20;
$paddingTop = 25;
$stringSpacing = 25;
$strings = [
    ['note' => 'E', 'stringNumber' => 4],
    ['note' => 'A', 'stringNumber' => 3],
    ['note' => 'D', 'stringNumber' => 2],
    ['note' => 'G', 'stringNumber' => 1],
];
$stringThickness = 2;
$totalHeight = totalHeight(count($strings), $stringSpacing, $stringThickness, $paddingTop, $paddingBottom);
$totalWidth = totalWidth($numberOfFrets, $fretThickness, $fretSpacing, $paddingLeft, $paddingRight);
$viewBoxBackgroundColor = '#f9f9f9';

?>

<svg class="fretboard" viewBox="0 0 <?= $totalWidth ?> <?= $totalHeight ?>" xmlns="http://www.w3.org/2000/svg" width="100%" style="background-color:<?= $viewBoxBackgroundColor ?>">

    <!-- board -->
    <?php $fretBoardXPosition = $paddingLeft ?>
    <?php $fretBoardYPosition = $paddingTop ?>
    <?php $fretBoardWidth = $totalWidth - $paddingLeft - $paddingRight ?>
    <?php $fretBoardHeight = $totalHeight - $paddingTop - $paddingBottom ?>
    <rect class="fretboard__board" x="<?= $fretBoardXPosition ?>" y="<?= $fretBoardYPosition ?>" width="<?= $fretBoardWidth ?>" height="<?= $fretBoardHeight ?>" fill="<?= $fretBoardFill ?>" />

    <?php if (!empty($config['showDots'])): ?>

        <!-- dots -->
        <?php foreach ($dots as $dot): ?>
            <?php $dotCxPosition = dotCxPosition($dot, $fretThickness, $fretSpacing, $paddingLeft) ?>
            <?php $dotCyPosition = dotCyPosition($totalHeight, $paddingTop, $paddingBottom) ?>
            <circle class="fretboard__dot" cx="<?= $dotCxPosition ?>" cy="<?= $dotCyPosition ?>" r="<?= $dotRadius ?>" fill="<?= $dotFill ?>" />
        <?php endforeach; ?>

        <!-- double dots -->
        <?php foreach ($doubleDots as $doubleDot): ?>
            <?php $dotCxPosition = doubleDotCxPosition($doubleDot, $fretThickness, $fretSpacing, $paddingLeft) ?>
            <?php $dotCyPositions = doubleDotCyPosition($totalHeight, $paddingTop, $paddingBottom, $stringThickness, $stringSpacing) ?>
            <?php foreach ($dotCyPositions as $dotCyPosition): ?>
                <circle class="fretboard__dot fretboard__dot--double" cx="<?= $dotCxPosition ?>" cy="<?= $dotCyPosition ?>" r="<?= $dotRadius ?>" fill="<?= $dotFill ?>" />
            <?php endforeach; ?>
        <?php endforeach; ?>

    <?php endif; ?>

    <!-- frets -->
    <?php foreach (range(0, $numberOfFrets) as $fretNumber): ?>
        <?php $fretHeight = fretHeight($totalHeight, $paddingTop, $paddingBottom) ?>
        <?php $fretXPosition = fretXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft) ?>
        <?php $fretYPosition = fretYPosition($paddingTop) ?>
        <rect class="fretboard__fret" x="<?= $fretXPosition ?>" y="<?= $fretYPosition ?>" width="<?= $fretThickness ?>" height="<?= $fretHeight ?>" fill="<?= $fretFill ?>" />
    <?php endforeach; ?>

    <!-- fret numbers -->
    <?php if (!empty($config['showFretNumbers'])): ?>
        <?php foreach (range(0, $numberOfFrets) as $fretNumber): ?>
            <?php $fretNumberXPosition = fretNumberXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft) ?>
            <?php $fretNumberYPosition = fretNumberYPosition($totalHeight, $paddingBottom, $noteHeight, $fretNumberSize) ?>
            <text class="fretboard__fretNumber" x="<?= $fretNumberXPosition ?>" y="<?= $fretNumberYPosition ?>" font-size="<?= $fretNumberSize ?>"><?= $fretNumber ?></text>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- strings -->
    <?php foreach ($strings as $string): ?>
        <?php $stringLength = stringLength($totalWidth, $paddingLeft, $paddingRight) ?>
        <?php $stringXPosition = stringXPosition($paddingLeft) ?>
        <?php $stringYPosition = stringYPosition($string['stringNumber'], $stringSpacing, $stringThickness, $paddingTop, $paddingBottom) ?>
        <rect class="fretboard__string" x="<?= $stringXPosition ?>" y="<?= $stringYPosition ?>" width="<?= $stringLength ?>" height="<?= $stringThickness ?>" />
    <?php endforeach; ?>

    <!-- string names -->
    <?php if (!empty($config['showStringNames'])): ?>
        <?php foreach ($strings as $string): ?>
            <?php $stringNameXPosition = stringNameXPosition($paddingLeft) ?>
            <?php $stringNameYPosition = stringNameYPosition($string['stringNumber'], $stringSpacing, $stringThickness, $paddingTop, $paddingBottom) ?>
            <text class="fretboard__stringName" x="<?= $stringNameXPosition ?>" y="<?= $stringNameYPosition ?>"><?= $string['note'] ?></text>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- notes -->
    <?php foreach ($notes as $note): ?>
        <?php [$stringNumber, $fretNumber, $noteFunction, $noteSign, $noteLabel, $fingering] = stringNumberFromNote($note, $strings) ?>
        <?php $noteXPosition = noteXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft) ?>
        <?php $noteYPosition = noteYPosition($stringNumber, $stringThickness, $stringSpacing, $paddingTop) ?>
        <?php $noteFingerXPosition = noteFingerXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft, $noteWidth, $noteFingerSize) ?>
        <?php $noteFingerYPosition = noteFingerYPosition($stringNumber, $stringThickness, $stringSpacing, $paddingTop, $noteHeight, $noteFingerSize) ?>
        <g class="fretboard__note">
            <rect class="fretboard__noteSymbol <?php if ($config['colors'] === 'intervals'): ?>fretboard__noteSymbol--<?= $noteFunction ?><?php endif; ?>" x="<?= $noteXPosition ?>" y="<?= $noteYPosition ?>" width="<?= $noteWidth ?>" height="<?= $noteHeight ?>" rx="<?= $noteRadius ?>" fill="<?= $noteFill ?>" />
            <text class="fretboard__noteText <?php if ($config['colors'] === 'intervals'): ?>fretboard__noteText--<?= $noteFunction ?><?php endif; ?>" x="<?= $noteXPosition + ($noteWidth / 2) ?>" y="<?= $noteYPosition + ($noteHeight / 2) ?>" font-size="<?= $noteLabelSize ?>" dominant-baseline="central" text-anchor="middle" fill="#ffffff">
                <tspan class="fretboard__noteTextSign" font-size="<?= $noteLabelSize * 0.9 ?>"><?= $noteSign ?></tspan>
                <tspan class="fretboard__noteTextLabel"><?= $noteLabel ?></tspan>
            </text>
            <text class="fretboard__noteFinger" x="<?= $noteFingerXPosition ?>" y="<?= $noteFingerYPosition ?>" fill="<?= $noteFingerColor ?>" font-size="<?= $noteFingerSize ?>"><?= $fingering ?></text>
        </g>
    <?php endforeach; ?>

</svg>

<?php if (!empty($_GET['debug'])): ?>
    <ul style="margin-top:2rem">
        <li>ViewBox Background Color = <?= $viewBoxBackgroundColor ?></li>
        <li>Total Width = <?= $totalWidth ?></li>
        <li>Total Height = <?= $totalHeight ?></li>
        <li>Padding Top = <?= $paddingTop ?></li>
        <li>Padding Right = <?= $paddingRight ?></li>
        <li>Padding Bottom = <?= $paddingBottom ?></li>
        <li>Padding Left = <?= $paddingLeft ?></li>
        <li>Number Of Frets = <?= $numberOfFrets ?></li>
        <li>Strings = <?= json_encode($strings) ?></li>
        <li>String Spacing = <?= $stringSpacing ?></li>
    </ul>
<?php endif; ?>
