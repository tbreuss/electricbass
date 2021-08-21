<?php

/**
 * @var app\models\Advertisement $model
 * @var yii\web\View $this
 */

$this->title = 'Inserat aktualisieren | Bassmarkt';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserat aktualisieren';
?>

<div class="content">
    <h1>Inserat aktualisieren</h1>

    <?= $this->render('_form', array('model' => $model)) ?>
</div>
