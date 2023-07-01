<?php $this->context->layout = 'empty' ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A-3-R', 'A-5-2', 'D-2-3', 'D-3-4', 'D-5-5', 'G-2-6', 'G-4-7', 'G-5-R']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['A-3-R', 'A-4-d2', 'A-6-m3', 'D-3-4', 'D-4-d5', 'D-6-a5', 'G-3-b7', 'G-5-R']
]) ?>

<?= app\widgets\Fretboard::widget([
    'notes' => ['E-0-X', 'A-0-X', 'D-0-X', 'G-0-X']
]) ?>
