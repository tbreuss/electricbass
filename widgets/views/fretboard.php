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
        $noteName = (string)$noteParts[0];
        $fretNumber = (int)$noteParts[1];
        $noteFunction = (string)$noteParts[2];
        $noteLabel = $noteFunction;

        $flatOrShartSign = substr($noteLabel, 0, 1);

        if (in_array($flatOrShartSign, ['d', 'b'])) {
            $noteLabel = '&#9837;' . substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'a') {
            $noteLabel = '&#9839;' . substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'm') {
            $noteLabel = $flatOrShartSign;
        }

        foreach ($strings as $string) {
            if ($string['note'] === $noteName) {
                return [$string['stringNumber'], $fretNumber, $noteFunction, $noteLabel];
            }
        }

        throw new \RuntimeException('Could not determine string number from note');
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
$fretNumberColor = '#cccccc';
$fretNumberSize = 13;
$fretSpacing = 60;
$fretThickness = 5;
$noteFill = '#bb0000';
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
        <?php [$stringNumber, $fretNumber, $noteFunction, $noteLabel] = stringNumberFromNote($note, $strings) ?>
        <?php $noteXPosition = noteXPosition($fretNumber, $fretThickness, $fretSpacing, $paddingLeft) ?>
        <?php $noteYPosition = noteYPosition($stringNumber, $stringThickness, $stringSpacing, $paddingTop) ?>
        <?php $noteLabelXPosition = noteLabelXPosition($noteXPosition, $noteFunction) ?>
        <?php $noteLabelYPosition = noteLabelYPosition($noteYPosition, $noteFunction) ?>
        <rect class="fretboard__note fretboard__note--<?= $noteFunction ?>" x="<?= $noteXPosition ?>" y="<?= $noteYPosition ?>" width="<?= $noteWidth ?>" height="<?= $noteHeight ?>" rx="<?= $noteRadius ?>" fill="<?= $noteFill ?>" />
        <text class="fretboard__noteText fretboard__noteText--<?= $noteFunction ?>" x="<?= $noteLabelXPosition ?>" y="<?= $noteLabelYPosition ?>" font-size="<?= $noteLabelSize ?>"><?= $noteLabel ?></text>
    <?php endforeach; ?>

</svg>

<style>
    .fretboard__note--R {
        fill: #ff0000;
    }
    .fretboard__note--d2 {
        fill: #ff6600;
    }    
    .fretboard__note--2 {
        fill: #ff9900;
    }
    .fretboard__note--m3 {
        fill: #FFCC00;
    }
    .fretboard__note--3 {
        fill: #ffff00;
    }
    .fretboard__note--4 {
        fill: #00aa00;
    }
    .fretboard__note--d5 {
        fill: #007777;
    }
    .fretboard__note--5 {
        fill: #0099ff;
    }
    .fretboard__note--a5 {
        fill: #6600cc;
    }    
    .fretboard__note--6 {
        fill: #660099;
    }
    .fretboard__note--b7 {
        fill: #990088;
    }
    .fretboard__note--7 {
        fill: #CC00AA;
    }
    .fretboard__noteText {
        font-weight: 600;
    }
    .fretboard__noteText--R {
        fill: #222222;
    }
    .fretboard__noteText--d2 {
        fill: #333333;
        letter-spacing: -0.25em;
    }
    .fretboard__noteText--2 {
        fill: #333333;
    }
    .fretboard__noteText--m3 {
        fill: #555555;
        letter-spacing: -0.25em;
    }    
    .fretboard__noteText--3 {
        fill: #333333;
    }
    .fretboard__noteText--4 {
        fill: #ffffff;
    }
    .fretboard__noteText--d5 {
        fill: #ffffff;
        letter-spacing: -0.25em;
    }    
    .fretboard__noteText--5 {
        fill: #ffffff;
    }
    .fretboard__noteText--a5 {
        fill: #ffffff;
        letter-spacing: -0.25em;
    }
    .fretboard__noteText--6 {
        fill: #ffffff;
    }
    .fretboard__noteText--b7 {
        fill: #ffffff;
        letter-spacing: -0.25em;
    }    
    .fretboard__noteText--7 {
        fill: #ffffff;
    }
    .fretboard__fretNumber {
        fill: <?= $fretNumberColor ?>;
    }
</style>

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
