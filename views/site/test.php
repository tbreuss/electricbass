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
    'notes' => ['A3-R', 'A4-d2', 'A6-m3', 'D3-4', 'D4-d5', 'D6-a5', 'G3-b7', 'G5-R']
]) ?>

<h2>Null-Bünde (Default-Farben)</h2>

<?= app\widgets\Fretboard::widget([
    'notes' => ['E0-X', 'A0-X', 'D0-X', 'G0-X']
]) ?>
