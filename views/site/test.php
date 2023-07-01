<?php $this->context->layout = 'empty' ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A3-R', 'A5-2', 'D2-3', 'D3-4', 'D5-5', 'G2-6', 'G4-7', 'G5-R']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A3-R', 'A4-d2', 'A6-m3', 'D3-4', 'D4-d5', 'D6-a5', 'G3-b7', 'G5-R']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['E0-X', 'A0-X', 'D0-X', 'G0-X']
]) ?>
