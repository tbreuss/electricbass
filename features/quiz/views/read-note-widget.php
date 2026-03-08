<?php

/** @var yii\web\View $this */ ?>

<?= $this->render('_quiz', [
    'title' => 'Bezeichne die Note',
    'mode' => 'read-note',
]) ?>

<script type="module">
    import { selectNote, showNote } from '<?= app\features\quiz\Asset::register($this)->baseUrl ?>/script.js';
    selectNote(14);
    showNote(14);
</script>
