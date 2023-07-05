<?php $this->context->layout = 'empty' ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A3-R-2', 'A5-2-4', 'D2-3-1', 'D3-4-2', 'D5-5-4', 'G2-6-1', 'G4-7-3', 'G5-R-4']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A3-R', 'A4-d2', 'A6-m3', 'D3-4', 'D4-d5', 'D6-a5', 'G3-b7', 'G5-R']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A3-C-2', 'A5-D-4', 'D2-E-1', 'D3-F-2', 'D5-G-4', 'G2-A-1', 'G4-B-3', 'G5-C-4']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['E0-X', 'A0-X', 'D0-X', 'G0-X']
]) ?>
