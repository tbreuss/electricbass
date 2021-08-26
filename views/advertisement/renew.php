<?php

/**
 * @var app\models\Advertisement $model
 * @var yii\web\View $this
 */

use app\helpers\Html;

$this->title = 'Inserat verlängert';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserat verlängert';
?>

<div class="content">
    <h1><?php echo $this->title ?></h1>

    <p>Dein Inserat <b><?php echo $model->title ?></b> wurde verlängert.</p>

    <p>Nach 60 Tagen wird es automatisch gelöscht.<br />
    Antworten auf dein Inserat erhälst du direkt per E-Mail.</p>

    <p><?php echo Html::a('Alle Inserate', ['advertisement/index']) ?></p>
</div>
