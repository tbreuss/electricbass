<?php

use function tebe\tonal\core\transpose;

?>

<h2>Form (Default-Farben)</h2>

<?php
const FRETS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
const STRINGS = [
    #'C', 
    'G', 
    'D', 
    'A', 
    'E', 
    #'B'
];
?>

<?php

$root = 'B';
$notesInStandardFormat = explode(' ', 'P1 M2 M3 A4 P5 M6 M7 P8');
$stretched = false;
$stretchedOffset = $stretched ? 1 : 0;

$TUNING = new tebe\tonal\fretboard\Tuning(
    'E-Bass',
    [
        //['C3', 'C'], 
        ['G2', 'G'], 
        ['D2', 'D'], 
        ['A1', 'A'], 
        ['E1', 'E'], 
        //['B0', 'B']
    ]
);

$notes = array_map(function ($interval) use ($root) {
    $transposed = tebe\tonal\core\distance\transpose($root, $interval);
    if (abs(tebe\tonal\note\get($transposed)->alt) > 1) { // @phpstan-ignore-line
        return [tebe\tonal\note\simplify($transposed), $interval];
    }
    return [$transposed, $interval];
}, $notesInStandardFormat);

$lowest = tebe\tonal\fretboard\findLowestNote($TUNING, $root);

$i = 1;
foreach(range(1, 8) as $position) {

    [$fretFrom, $fretTo] = tebe\tonal\fretboard\positionBound($position, $stretched);
    $fingerings = tebe\tonal\fretboard\findNotes($TUNING, $notes, fretFrom: $fretFrom, fretTo: $fretTo);
    
    // remove fingerings until root tone
    $firstRoot = null;
    $lastRoot = null;
    foreach ($fingerings as $index => $fingering) {
        if ($fingering['note'] === $root || $fingering['pc'] === $root) {
            if (is_null($firstRoot)) {
                $firstRoot = $index;
            }
            $lastRoot = $index;
        }
    }

    if (!is_null($firstRoot) && !is_null($lastRoot)) {
        $fingerings = array_slice($fingerings, $firstRoot, $lastRoot - $firstRoot + 1);
    }

    $uniqueNotesCount = count(array_unique(array_column($fingerings, 'abs')));
    if ($uniqueNotesCount % count($notes) <> 0) {
        continue;
    }

    #echo"<pre>";print_r($notes);echo"</pre>";
    #echo"<pre>";print_r($fingerings);echo"</pre>";

    echo "<h2>$position. Lage (Variante $i)</h2>";
    echo app\widgets\Fretboard::widget([
        'position' => $position,
        'positionStretched' => $stretched,
        'strings' => STRINGS,
        'frets' => FRETS,
        'colors' => 'diatonic',
        'notes' => array_map(fn($f) => $f['coord'], $fingerings),
        'root' => $lowest
    ]);

    $i++;
}

return;
?>


<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3', '5/3', '2/2', '3/2', '5/2', '2/1', '4/1', '5/1']
]) ?>

<h2>Form mit Notennamen (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'default',
    'notes' => ['3/3-C', '5/3-D', '2/2-E', '3/2-F', '5/2-G', '2/1-A', '4/1-B', '5/1-C']
]) ?>

<h2>Form mit Fingersatz (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'default',
    'notes' => ['3/3-2', '5/3-4', '2/2-1', '3/2-2', '5/2-4', '2/1-1', '4/1-3', '5/1-4']
]) ?>

<h2>Form mit Intervallen (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3-R', '5/3-2', '2/2-3', '3/2-4', '5/2-5', '2/1-6', '4/1-7', '5/1-R']
]) ?>

<h2>Form mit Intervallen und Fingersatz (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3-R-2', '5/3-2-4', '2/2-3-1', '3/2-4-2', '5/2-5-4', '2/1-6-1', '4/1-7-3', '5/1-R-4']
]) ?>

<h2>Form mit Intervallen (Intervall-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3-R', '5/3-2', '2/2-3', '3/2-4', '5/2-5', '2/1-6', '4/1-7', '5/1-R']
]) ?>

<h2>Form mit Intervallen und Fingersatz (Intervall-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3-R-2', '5/3-2-4', '2/2-3-1', '3/2-4-2', '5/2-5-4', '2/1-6-1', '4/1-7-3', '5/1-R-4']
]) ?>

<h2>Chromatische Tonleiter aufsteigend (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => [
        '3/3-C-1',
        '4/3-C#-2',
        '5/3-D-3',
        '6/3-D#-4',
        '2/2-E-1',
        '3/2-F-1',
        '4/2-F#-2',
        '5/2-G-3',
        '6/2-G#-4',
        '2/1-A-1',
        '3/1-A#-1',
        '4/1-B-2',
        '5/1-C-3'
    ]
]) ?>

<h2>Chromatische Tonleiter absteigend (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => [
        '3/3-C-1',
        '4/3-Db-2',
        '5/3-D-3',
        '6/3-Eb-4',
        '2/2-E-1',
        '3/2-F-1',
        '4/2-Gb-2',
        '5/2-G-3',
        '6/2-Ab-4',
        '2/1-A-1',
        '3/1-Bb-1',
        '4/1-B-2',
        '5/1-C-3'
    ]
]) ?>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'default',
    'notes' => ['0/4-R-4', '12/4-R-4', '3/3-R', '4/3-d2', '6/3-m3', '3/2-4', '4/2-d5', '6/2-a5', '3/1-b7', '5/1-R'],
    'root' => '3/3'
]) ?>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => ['3/3-R', '4/3-b2', '6/3-m3', '3/2-4', '4/2-b5', '6/2-#5', '3/1-b7', '5/1-R']
]) ?>

<h2>Null-Bünde (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'notes' => ['0/4-X', '0/3-X', '0/2-X', '0/1-X']
]) ?>


<script src="https://cdn.jsdelivr.net/npm/tonal/browser/tonal.min.js"></script>

<?php /*
<script>
    console.log(Tonal.Core.note("Abb4"));
    console.log(Tonal.Core.note("Axx4"));
    console.log(Tonal.Core.note("A"));
    console.log(Tonal.Core.note("A4"));
    console.log(Tonal.Core.note("A#4"));
    console.log(Tonal.Core.note("Ab4"));
</script>

<pre><?php print_r(tebe\tonal\core\note("Abb4")); ?></pre>
<pre><?php print_r(tebe\tonal\core\note("Axx4")); ?></pre>
<pre><?php print_r(tebe\tonal\core\note("A")); ?></pre>
<pre><?php print_r(tebe\tonal\core\note("A4")); ?></pre>
<pre><?php print_r(tebe\tonal\core\note("A#4")); ?></pre>
<pre><?php print_r(tebe\tonal\core\note("Ab4")); ?></pre>

<script>
    console.log(Tonal.Core.interval("P1"));
    console.log(Tonal.Core.interval("m3"));
    console.log(Tonal.Core.interval("M3"));
    console.log(Tonal.Core.interval("d5"));
    console.log(Tonal.Core.interval("P5"));
    console.log(Tonal.Core.interval("A5"));
    console.log(Tonal.Core.interval("m7"));
    console.log(Tonal.Core.interval("M7"));
    console.log(Tonal.Core.interval("P8"));
    console.log(Tonal.Core);
</script>

<pre><?php print_r(tebe\tonal\core\interval("P1")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("m3")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("M3")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("d5")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("P5")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("A5")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("m7")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("M7")); ?></pre>
<pre><?php print_r(tebe\tonal\core\interval("P8")); ?></pre>
*/ ?>

<script>
    console.log(Tonal.Core.transpose("C4", "5P"));
</script>

<?= join(', ', array_map(fn($interval) => transpose("C", $interval), ['P1', 'M3', 'P5', 'M7', 'M9', 'P11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("G", $interval), ['P1', 'm3', 'P5', 'm7', 'M9', 'P11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("G", $interval), ['P1', 'M3', 'P5', 'm7', 'M9', 'A11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("F#", $interval), ['P1', 'm2', 'm3', 'M3', 'P4', 'P5', 'm7', 'P8'])) ?><br>

<?php $tuning = new tebe\tonal\fretboard\Tuning('E-Bass', [['G2', 'G'], ['D2', 'D'], ['A1', 'A'], ['E1', 'E'], ['B0', 'B']]); ?>

<?php
$notes = tebe\tonal\fretboard\findNotes($tuning, ['C', 'D', 'E', 'F', 'G', 'A', 'B']);
echo app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['label'], $notes),
    'root' => '3/3'
]); ?>

<?php $notes = tebe\tonal\fretboard\findNotes($tuning, [['C', 'P1'], ['C#', 'A1'], ['D', 'M2'], ['D#', 'A2'], ['E', 'M3'], ['F', 'P4'], ['F#', 'A4'], ['G', 'P5'], ['G#', 'A5'], ['A', 'M6'], ['A#', 'A6'], ['B', 'M7']]);
echo app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['label'], $notes),
    'root' => '3/3'
]); ?>

<?php $notes = tebe\tonal\fretboard\findNotes($tuning, ['C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B']);
echo app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => array_map(fn($n) => $n['coord'] . '-' . $n['label'], $notes),
    'root' => '3/3'
]); ?>

<?php $test = array_map(fn($interval) => transpose("C", $interval), ['P1', 'M3', 'P5', 'M7', 'M9', 'P11', 'M13']);
$notes = tebe\tonal\fretboard\findNotes($tuning, $test);
echo app\widgets\Fretboard::widget([
    'strings' => STRINGS,
    'frets' => FRETS,
    'colors' => 'diatonic',
    'notes' => array_map(fn($n) => $n['coord'] . '-' . $n['label'], $notes),
    'root' => '3/3'
]); ?>
