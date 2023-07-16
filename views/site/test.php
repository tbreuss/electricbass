<?php $this->context->layout = 'empty' ?>

<?php
/**
 * Links
 * https://en.wikipedia.org/wiki/Interval_(music)
 * 
 */ 
?>

<h2>Form (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'default',
    'notes' => ['A3', 'A5', 'D2', 'D3', 'D5', 'G2', 'G4', 'G5']
]) ?>

<h2>Form mit Notennamen (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'default',
    'notes' => ['A3-C', 'A5-D', 'D2-E', 'D3-F', 'D5-G', 'G2-A', 'G4-B', 'G5-C']
]) ?>

<h2>Form mit Fingersatz (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'default',
    'notes' => ['A3-2', 'A5-4', 'D2-1', 'D3-2', 'D5-4', 'G2-1', 'G4-3', 'G5-4']
]) ?>

<h2>Form mit Intervallen (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'default',
    'notes' => ['A3-R', 'A5-2', 'D2-3', 'D3-4', 'D5-5', 'G2-6', 'G4-7', 'G5-R']
]) ?>

<h2>Form mit Intervallen und Fingersatz (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'default',
    'notes' => ['A3-R-2', 'A5-2-4', 'D2-3-1', 'D3-4-2', 'D5-5-4', 'G2-6-1', 'G4-7-3', 'G5-R-4']
]) ?>

<h2>Form mit Intervallen (Intervall-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'intervals',
    'notes' => ['A3-R', 'A5-2', 'D2-3', 'D3-4', 'D5-5', 'G2-6', 'G4-7', 'G5-R']
]) ?>

<h2>Form mit Intervallen und Fingersatz (Intervall-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'colors' => 'intervals',
    'notes' => ['A3-R-2', 'A5-2-4', 'D2-3-1', 'D3-4-2', 'D5-5-4', 'G2-6-1', 'G4-7-3', 'G5-R-4']
]) ?>

<h2>Chromatische Tonleiter aufsteigend (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'notes' => [
        'A3-C-1', 
        'A4-C#-2', 
        'A5-D-3', 
        'A6-D#-4', 
        'D2-E-1', 
        'D3-F-1', 
        'D4-F#-2', 
        'D5-G-3', 
        'D6-G#-4', 
        'G2-A-1', 
        'G3-A#-1', 
        'G4-B-2', 
        'G5-C-3'
    ]
]) ?>

<h2>Chromatische Tonleiter absteigend (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'notes' => [
        'A3-C-1', 
        'A4-Db-2', 
        'A5-D-3', 
        'A6-Eb-4', 
        'D2-E-1', 
        'D3-F-1', 
        'D4-Gb-2', 
        'D5-G-3', 
        'D6-Ab-4', 
        'G2-A-1', 
        'G3-Bb-1', 
        'G4-B-2', 
        'G5-C-3'
    ]
]) ?>

<?= app\widgets\Fretboard::widget([
    'colors' => 'intervals',
    'notes' => ['E0-R-4', 'E12-R-4', 'A3-R', 'A4-d2', 'A6-m3', 'D3-4', 'D4-d5', 'D6-a5', 'G3-b7', 'G5-R']
]) ?>

<h2>Null-Bünde (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'notes' => ['E0-X', 'A0-X', 'D0-X', 'G0-X']
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

<?php use function tebe\tonal\core\transpose; ?>

<?= join(', ', array_map(fn($interval) => transpose("C", $interval), ['P1', 'M3', 'P5', 'M7', 'M9', 'P11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("G", $interval), ['P1', 'm3', 'P5', 'm7', 'M9', 'P11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("G", $interval), ['P1', 'M3', 'P5', 'm7', 'M9', 'A11', 'M13'])) ?><br>
<?= join(', ', array_map(fn($interval) => transpose("F#", $interval), ['P1', 'm2', 'm3', 'M3', 'P4', 'P5', 'm7', 'P8'])) ?><br>
