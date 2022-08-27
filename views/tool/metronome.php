<?php

/**
 * @var yii\web\View $this
 */

use app\widgets\Metronome;

$this->title = 'Tools';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = 'Metronom';
?>

<?= Metronome::widget();
